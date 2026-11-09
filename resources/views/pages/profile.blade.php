@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<header class="page-heading compact"><p class="eyebrow">BEHIND THE TASK LIST</p><h1>Profile</h1><p class="lead">The account associated with this task-management application.</p></header>
<section class="profile-panel" aria-labelledby="profile-name">
    <div class="profile-summary"><span class="profile-avatar" aria-hidden="true">{{ strtoupper(substr($user->full_name, 0, 1)) }}</span><p class="eyebrow">ACCOUNT HOLDER</p><h2 id="profile-name">{{ $user->full_name }}</h2><p>{{ $user->username }}</p></div>
    <div class="profile-details"><h2>Account information</h2><dl><div><dt>Full name</dt><dd>{{ $user->full_name }}</dd></div><div><dt>Username</dt><dd>{{ $user->username }}</dd></div><div><dt>Email</dt><dd>{{ $user->email }}</dd></div></dl><a class="text-link" href="{{ route('about') }}">About the developer &rarr;</a></div>
</section>
@endsection
