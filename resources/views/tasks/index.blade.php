@extends('layouts.app')

@section('title', 'Task List')

@section('content')
<header class="page-heading compact"><p class="eyebrow">THE COMPLETE PICTURE</p><h1>Task List</h1><p class="lead">Every saved task, ordered by scheduled date from earliest to latest.</p></header>
<section class="directory" aria-labelledby="all-tasks-title">
    <div class="directory-heading"><div><h2 id="all-tasks-title">All tasks</h2><p>{{ $tasks->count() }} saved tasks across all dates</p></div><a class="text-link" href="{{ route('home') }}">Back to today &rarr;</a></div>
    @include('tasks.partials.table', ['tasks' => $tasks, 'emptyMessage' => 'No tasks have been added yet.'])
    <div class="directory-footer"><span>Sorted by task date &middot; Earliest first</span><span>{{ $tasks->count() }} total tasks</span></div>
</section>
@endsection
