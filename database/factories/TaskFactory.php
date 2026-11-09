<?php

namespace Database\Factories;

use App\Models\Task;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(5),
            'status' => 'pending',
            'task_date' => CarbonImmutable::today(config('tasks.timezone'))->toDateString(),
        ];
    }
}
