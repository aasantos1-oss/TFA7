<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ProfileSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['tasks.timezone' => 'Asia/Manila']);
        $this->travelTo(CarbonImmutable::parse('2026-10-31 00:15:00', 'Asia/Manila'));
        $this->seed(DatabaseSeeder::class);
    }

    protected function tearDown(): void
    {
        $this->travelBack();
        parent::tearDown();
    }

    public function test_four_pages_render_with_shared_navigation(): void
    {
        foreach (['/', '/tasks', '/profile', '/about'] as $path) {
            $response = $this->get($path)->assertOk();
            foreach (['home', 'tasks.index', 'profile.show', 'about'] as $name) {
                $response->assertSee('href="'.route($name).'"', false);
            }
        }
    }

    public function test_seed_data_has_eight_tasks_three_dates_and_one_user(): void
    {
        $this->assertGreaterThanOrEqual(8, Task::count());
        $this->assertGreaterThanOrEqual(3, Task::query()->distinct()->count('task_date'));
        $this->assertDatabaseCount('users', 1);
        $this->assertTrue(Task::query()->forDate('2026-10-31')->exists());
    }

    public function test_new_task_defaults_to_pending_in_the_database(): void
    {
        $task = Task::create(['title' => 'Check the default status', 'task_date' => '2026-10-31']);
        $this->assertSame('pending', $task->fresh()->status);
    }

    public function test_welcome_page_displays_only_todays_database_tasks(): void
    {
        $response = $this->get('/')->assertOk()->assertViewIs('pages.welcome');
        $response->assertViewHas('tasks', fn ($tasks) =>
            $tasks instanceof Collection && $tasks->count() === 3
            && $tasks->every(fn ($task) => $task->task_date->toDateString() === '2026-10-31')
        );
        foreach (Task::all() as $task) {
            if ($task->task_date->toDateString() === '2026-10-31') {
                $response->assertSee($task->title);
            } else {
                $response->assertDontSee($task->title);
            }
        }
    }

    public function test_today_uses_the_philippine_date_across_the_utc_boundary(): void
    {
        $this->assertSame('2026-10-30', CarbonImmutable::now('UTC')->toDateString());
        $this->get('/')->assertOk()->assertViewHas('today', fn ($date) =>
            $date->toDateString() === '2026-10-31'
        );
    }

    public function test_task_list_shows_all_records_in_date_order(): void
    {
        $response = $this->get('/tasks')->assertOk()->assertViewIs('tasks.index');
        $rows = $response->viewData('tasks');
        $this->assertInstanceOf(Collection::class, $rows);
        $this->assertCount(Task::count(), $rows);
        $dates = $rows->map(fn ($task) => $task->task_date->toDateString())->all();
        $sorted = $dates;
        sort($sorted);
        $this->assertSame($sorted, $dates);
        foreach ($rows as $task) {
            $response->assertSee($task->title);
        }
    }

    public function test_profile_displays_the_single_database_user(): void
    {
        $user = User::query()->sole();
        $this->get('/profile')->assertOk()->assertViewIs('pages.profile')
            ->assertSee($user->username)->assertSee($user->full_name)->assertSee($user->email)
            ->assertDontSee($user->password);
    }

    public function test_about_is_static_and_identifies_the_developer(): void
    {
        User::query()->delete();
        $this->get('/about')->assertOk()->assertSee(config('tasks.developer_name'));
    }

    public function test_seeding_twice_does_not_duplicate_rows_or_reset_passwords(): void
    {
        $hash = User::query()->sole()->password;
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('tasks', 8);
        $this->assertDatabaseCount('users', 1);
        $this->assertSame($hash, User::query()->sole()->password);
    }

    public function test_reseeding_on_a_later_day_refreshes_sample_dates(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-01 12:00:00', 'Asia/Manila'));
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('tasks', 8);
        $this->assertSame(3, Task::query()->forDate('2026-11-01')->count());
        $this->assertSame(3, Task::query()->distinct()->count('task_date'));
    }

    public function test_seed_refuses_to_delete_unrelated_users(): void
    {
        User::factory()->create(['username' => 'another.user']);
        try {
            $this->app->make(ProfileSeeder::class)->run();
            $this->fail('A database with multiple users must be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('separate TSA3 database', $exception->getMessage());
        }
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('tasks', 8);
    }

    public function test_unique_usernames_are_enforced_by_the_database(): void
    {
        $this->expectException(QueryException::class);
        User::factory()->create(['username' => config('tasks.profile_username')]);
    }

    public function test_no_tasks_today_has_an_empty_state_without_losing_other_dates(): void
    {
        Task::query()->forDate('2026-10-31')->delete();
        $this->get('/')->assertOk()->assertSee('No tasks scheduled for today.');
        $this->assertDatabaseCount('tasks', 5);
        $this->get('/tasks')->assertOk()->assertViewHas('tasks', fn ($tasks) => $tasks->count() === 5);
    }

    public function test_task_titles_are_escaped_when_rendered(): void
    {
        $title = '<script>alert("example")</script>';
        Task::create(['title' => $title, 'task_date' => '2026-10-31']);
        $this->get('/')->assertOk()->assertSee($title)->assertDontSee($title, false);
    }
}
