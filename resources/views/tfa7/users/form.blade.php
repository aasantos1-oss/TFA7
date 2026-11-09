@extends('tfa7.layout')
@section('title', $user->exists ? 'Edit user' : 'Create user')
@section('content')
<h1>{{ $user->exists ? 'Edit user' : 'Create user' }}</h1>
<form class="panel" method="POST" enctype="multipart/form-data" action="{{ $user->exists ? route('tfa7.users.update', $user) : route('tfa7.users.store') }}">
    @csrf
    @if ($user->exists) @method('PUT') @endif
    <label for="username">Username</label>
    <input id="username" name="username" value="{{ old('username', $user->username) }}" required maxlength="255" aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}">
    @error('username')<p class="error">{{ $message }}</p>@enderror
    <label for="full_name">Full name</label>
    <input id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required maxlength="255" aria-invalid="{{ $errors->has('full_name') ? 'true' : 'false' }}">
    @error('full_name')<p class="error">{{ $message }}</p>@enderror
    @if ($hasEmail)
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255">
    @error('email')<p class="error">{{ $message }}</p>@enderror
    @endif
    @if ($hasPassword)
    <label for="password">{{ $user->exists ? 'New password (optional)' : 'Password' }}</label>
    <input id="password" type="password" name="password" minlength="8" maxlength="72" autocomplete="new-password" @if (! $user->exists) required @endif>
    <p class="help">At least 8 characters. @if ($user->exists) Leave blank to keep the current password. @endif</p>
    @error('password')<p class="error">{{ $message }}</p>@enderror
    <label for="password_confirmation">Confirm password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" @if (! $user->exists) required @endif>
    @endif
    @if ($user->exists)
    <label for="avatar">Profile picture (optional)</label>
    @include('tfa7.avatar', ['user' => $user])
    <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png" aria-describedby="avatar-help">
    <p class="help" id="avatar-help">JPG or PNG, maximum 2 MB and 4096 × 4096 pixels. Saved as a 256 × 256 JPEG. Leave empty to keep the current picture. After a validation error, select the file again.</p>
    @error('avatar')<p class="error">{{ $message }}</p>@enderror
    @else
    <p class="help">After creating the user, click Edit to upload a profile picture.</p>
    @endif
    <div class="actions"><button type="submit">Save user</button><a href="{{ route('tfa7.users.index') }}">Cancel</a></div>
</form>
@endsection
