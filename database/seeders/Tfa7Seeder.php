<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Tfa7Seeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['username' => config('tasks.profile_username')], [
            'full_name' => config('tasks.developer_name'),
            'name' => config('tasks.developer_name'),
            'email' => config('tasks.profile_email'),
            'password' => Str::random(64),
        ]);

        Customer::firstOrCreate(['email' => 'sample.customer@example.com'], [
            'full_name' => 'Sample Customer',
        ]);

        $this->call(TaskSeeder::class);
    }
}
