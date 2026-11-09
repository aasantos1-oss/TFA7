<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function today(): View
    {
        // Compute the date once so the heading and query agree at midnight.
        $today = CarbonImmutable::today(config('tasks.timezone'));
        $tasks = Task::query()->forDate($today)->orderBy('id')->get();

        return view('pages.welcome', [
            'today' => $today,
            'tasks' => $tasks,
        ]);
    }

    public function index(): View
    {
        $tasks = Task::query()->orderBy('task_date')->orderBy('id')->get();

        return view('tasks.index', ['tasks' => $tasks]);
    }
}
