@extends('tfa7.layout')
@section('title', 'User accounts')
@section('content')
<div class="heading"><h1>User accounts</h1><a class="button" href="{{ route('tfa7.users.create') }}">Create user</a></div>
<div class="panel table-wrap"><table><thead><tr><th>Avatar</th><th>Username</th><th>Full name</th><th>Action</th></tr></thead><tbody>
@forelse ($users as $user)
<tr><td>@include('tfa7.avatar', ['user' => $user])</td><td>{{ $user->username }}</td><td>{{ $user->full_name }}</td><td><a href="{{ route('tfa7.users.edit', $user) }}">Edit</a></td></tr>
@empty<tr><td colspan="4">No users yet.</td></tr>@endforelse
</tbody></table></div>
@include('tfa7.pagination', ['records' => $users])
@endsection
