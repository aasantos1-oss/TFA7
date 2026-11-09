@extends('layouts.app')

@section('title', 'About')

@section('content')
<header class="page-heading"><p class="eyebrow">ABOUT THE APPLICATION</p><h1>A little structure<br>for your everyday tasks.</h1><p class="lead">Tasks for Today brings a daily overview, a complete task list, and a user profile into one straightforward application.</p></header>
<div class="about-grid">
    <section class="content-panel"><p class="eyebrow">THE DEVELOPER</p><h2>{{ config('tasks.developer_name') }}</h2><p>I developed this application for IT0049 Web System Technologies, Technical Summative Assessment 3. It demonstrates a Laravel application that retrieves saved tasks and presents them across distinct pages.</p><p>The Welcome page focuses on today's work. The Task List shows tasks across all dates, while the Profile page displays the application user's account information.</p><a class="text-link" href="{{ route('profile.show') }}">View profile &rarr;</a></section>
    <section class="content-panel"><p class="eyebrow">FIND YOUR WAY</p><h2>One place to see your work.</h2><ol class="flow-list"><li><strong>Welcome</strong><p>See the tasks scheduled for the current date.</p></li><li><strong>Task List</strong><p>Browse every task in date order.</p></li><li><strong>Profile</strong><p>View the application's single account holder.</p></li></ol><a class="text-link" href="{{ route('home') }}">See today's tasks &rarr;</a></section>
</div>
<p class="scope-note">Dates on the Welcome page follow {{ config('tasks.timezone') }}. Task status is displayed for reference; this assessment provides read-only pages.</p>
@endsection
