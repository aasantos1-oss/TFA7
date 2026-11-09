<?php

use App\Models\Customer;
use App\Models\User;
use Database\Seeders\Tfa7Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('creates customers and prefills the edit form', function () {
    $this->post('/customers', ['full_name' => 'Sample Buyer', 'email' => 'buyer@example.com'])
        ->assertRedirect(route('tfa7.customers.index'));
    $this->assertDatabaseHas('customers', ['full_name' => 'Sample Buyer', 'email' => 'buyer@example.com']);
    $customer = Customer::sole();
    $this->get('/customers/'.$customer->id.'/edit')->assertOk()->assertSee('Sample Buyer');
});

it('rejects invalid customer input and preserves the entered name', function () {
    $this->from('/customers/create')->post('/customers', ['full_name' => 'Preserve Me', 'email' => 'invalid'])
        ->assertRedirect('/customers/create')->assertSessionHasErrors('email')
        ->assertSessionHasInput('full_name', 'Preserve Me');
    $this->assertDatabaseCount('customers', 0);
});

it('requires customer fields', function () {
    $this->post('/customers', [])->assertSessionHasErrors(['full_name', 'email']);
    $this->assertDatabaseCount('customers', 0);
});

it('updates an existing customer without adding another record', function () {
    $customer = Customer::factory()->create();
    $this->put('/customers/'.$customer->id, ['full_name' => 'Changed Buyer', 'email' => 'changed@example.com'])
        ->assertRedirect(route('tfa7.customers.index'));
    $this->assertDatabaseCount('customers', 1);
    $this->assertDatabaseHas('customers', ['id' => $customer->id, 'full_name' => 'Changed Buyer']);
});

it('creates a user with a hashed password and synchronized name', function () {
    $this->post('/users', ['username' => 'new.user', 'full_name' => 'New User', 'email' => 'new@example.com',
        'password' => 'Example123!', 'password_confirmation' => 'Example123!'])
        ->assertRedirect(route('tfa7.users.index'));
    $user = User::sole();
    expect($user->name)->toBe('New User');
    expect(Hash::check('Example123!', $user->password))->toBeTrue();
});

it('requires user fields and rejects duplicate usernames', function () {
    $this->post('/users', [])->assertSessionHasErrors(['username', 'full_name', 'email', 'password']);
    $existing = User::factory()->create();
    $this->post('/users', ['username' => $existing->username, 'full_name' => 'Duplicate', 'email' => 'duplicate@example.com',
        'password' => 'Example123!', 'password_confirmation' => 'Example123!'])->assertSessionHasErrors('username');
    $this->assertDatabaseCount('users', 1);
});

it('allows an unchanged username and keeps a blank password unchanged', function () {
    $user = User::factory()->create();
    $hash = $user->password;
    $this->put('/users/'.$user->id, ['username' => $user->username, 'full_name' => 'Updated Name', 'email' => $user->email, 'password' => ''])
        ->assertSessionHasNoErrors()->assertRedirect(route('tfa7.users.index'));
    expect($user->fresh()->password)->toBe($hash);
    expect($user->fresh()->full_name)->toBe('Updated Name');
});

it('rejects another users username during update', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $this->put('/users/'.$user->id, ['username' => $other->username, 'full_name' => $user->full_name, 'email' => $user->email])
        ->assertSessionHasErrors('username');
    expect($user->fresh()->username)->toBe($user->username);
});

it('stores a prepared avatar and replaces only the old generated image', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $data = ['username' => $user->username, 'full_name' => $user->full_name, 'email' => $user->email];
    $this->put('/users/'.$user->id, $data + ['avatar' => UploadedFile::fake()->image('photo.png', 600, 400)])
        ->assertSessionHasNoErrors()->assertRedirect(route('tfa7.users.index'));
    $old = $user->fresh()->avatar;
    Storage::disk('public')->assertExists($old);
    $size = getimagesize(Storage::disk('public')->path($old));
    expect([$size[0], $size[1], $size['mime']])->toBe([256, 256, 'image/jpeg']);
    $this->get('/users')->assertOk()->assertSee(Storage::disk('public')->url($old));
    $this->put('/users/'.$user->id, $data + ['avatar' => UploadedFile::fake()->image('replacement.jpg')])
        ->assertSessionHasNoErrors();
    Storage::disk('public')->assertMissing($old);
    Storage::disk('public')->assertExists($user->fresh()->avatar);
});

it('rejects oversized and nonimage files without changing the saved avatar', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $data = ['username' => $user->username, 'full_name' => $user->full_name, 'email' => $user->email];
    foreach ([UploadedFile::fake()->image('large.jpg')->size(2049), UploadedFile::fake()->create('fake.jpg', 10, 'text/plain')] as $file) {
        $this->put('/users/'.$user->id, $data + ['avatar' => $file])->assertSessionHasErrors('avatar');
    }
    expect($user->fresh()->avatar)->toBeNull();
    expect(Storage::disk('public')->allFiles())->toBe([]);
});

it('shows the placeholder and escapes account names', function () {
    $user = User::factory()->create(['full_name' => '<script>alert(1)</script>']);
    $this->get('/users')->assertOk()->assertSee('tfa7-avatar-placeholder.svg')
        ->assertSee($user->full_name)->assertDontSee($user->full_name, false);
});

it('keeps the profile usable with multiple accounts and can reseed safely', function () {
    $this->seed(Tfa7Seeder::class);
    $extra = User::factory()->create();
    $this->seed(Tfa7Seeder::class);
    $this->assertDatabaseCount('users', 2);
    $this->assertModelExists($extra);
    $this->get('/profile')->assertOk()->assertSee(config('tasks.profile_username'));
});
