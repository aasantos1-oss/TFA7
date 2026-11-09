<?php

namespace Database\Seeders;

use App\Models\Task;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $today = CarbonImmutable::today(config('tasks.timezone'));
        $records = [
            ['title' => 'Review project requirements', 'status' => 'completed', 'offset' => -1],
            ['title' => 'Prepare the database schema', 'status' => 'completed', 'offset' => -1],
            ['title' => 'Organize Laravel project files', 'status' => 'pending', 'offset' => -1],
            ['title' => 'Review today\'s task list', 'status' => 'pending', 'offset' => 0],
            ['title' => 'Implement Eloquent task queries', 'status' => 'in_progress', 'offset' => 0],
            ['title' => 'Verify profile information', 'status' => 'completed', 'offset' => 0],
            ['title' => 'Test task date filtering', 'status' => 'pending', 'offset' => 1],
            ['title' => 'Prepare repository submission', 'status' => 'pending', 'offset' => 1],
        ];

        foreach ($records as $record) {
            // Stable titles let reruns refresh sample dates without duplicating tasks.
            Task::updateOrCreate(
                ['title' => $record['title']],
                [
                    'status' => $record['status'],
                    'task_date' => $today->addDays($record['offset'])->toDateString(),
                ],
            );
        }
    }
}
