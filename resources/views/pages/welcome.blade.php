@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<section class="welcome-heading">
    <div><p class="eyebrow">YOUR DAILY OVERVIEW</p><h1>Make room for<br>what matters today.</h1><p class="lead">A clear view of your scheduled tasks, so you can take the day one thing at a time.</p></div>
    <aside class="date-card" aria-label="Today's date"><span class="date-day">{{ $today->format('d') }}</span><div><strong>{{ $today->format('l') }}</strong><span>{{ $today->format('F Y') }}</span><span class="timezone-label">{{ config('tasks.timezone') }}</span></div></aside>
</section>
<section class="stats-grid" aria-label="Today's task counts">
    <div class="stat-card"><span>Scheduled today</span><strong>{{ $tasks->count() }}</strong><small>Tasks on today's list</small></div>
    <div class="stat-card"><span>Still open</span><strong>{{ $tasks->where('status', '!=', 'completed')->count() }}</strong><small>Pending or in progress</small></div>
    <div class="stat-card"><span>Completed</span><strong>{{ $tasks->where('status', 'completed')->count() }}</strong><small>Finished tasks for today</small></div>
</section>
<section class="directory" aria-labelledby="today-title">
    <div class="directory-heading"><div><h2 id="today-title">Today's tasks</h2><p>{{ $today->format('F j, Y') }} &middot; {{ $tasks->count() }} scheduled</p></div><a class="text-link" href="{{ route('tasks.index') }}">View all tasks &rarr;</a></div>
    @include('tasks.partials.table', ['tasks' => $tasks, 'emptyMessage' => 'No tasks scheduled for today.'])
    <div class="directory-footer"><span>Only tasks scheduled for today</span><span>Read-only task directory</span></div>
</section>
@endsection
