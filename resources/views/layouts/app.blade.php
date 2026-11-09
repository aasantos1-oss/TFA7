<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tasks for Today: a simple daily task directory with a complete task list and developer profile.">
    <title>@yield('title', 'Welcome') | Tasks for Today</title>
    <link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Tasks for Today welcome page">
                <span class="brand-symbol" aria-hidden="true">T<span>+</span></span>
                <span>tasks for today<span class="brand-subtitle">ONE DAY AT A TIME</span></span>
            </a>
            <nav aria-label="Main navigation">
                <a href="{{ route('tfa7.customers.index') }}">Customers</a>
                <a href="{{ route('tfa7.users.index') }}">User Accounts</a>
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Welcome</a>
                <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}" @if(request()->routeIs('tasks.index')) aria-current="page" @endif>Task List</a>
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'active' : '' }}" @if(request()->routeIs('profile.show')) aria-current="page" @endif>Profile</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}" @if(request()->routeIs('about')) aria-current="page" @endif>About</a>
            </nav>
        </div>
    </header>
    <main id="main-content" class="page-container" tabindex="-1">
        @yield('content')
    </main>
    <footer class="site-footer">
        <div class="footer-inner"><span>Tasks for Today</span><span>IT0049 / TSA3 &middot; Developed by {{ config('tasks.developer_name') }}</span></div>
    </footer>
</body>
</html>
