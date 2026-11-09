@extends('tfa7.layout')
@section('title', $customer->exists ? 'Edit customer' : 'Create customer')
@section('content')
<h1>{{ $customer->exists ? 'Edit customer' : 'Create customer' }}</h1>
<form class="panel" method="POST" action="{{ $customer->exists ? route('tfa7.customers.update', $customer) : route('tfa7.customers.store') }}">
    @csrf
    @if ($customer->exists) @method('PUT') @endif
    <label for="full_name">Full name</label>
    <input id="full_name" name="full_name" value="{{ old('full_name', $customer->full_name) }}" maxlength="255" required aria-invalid="{{ $errors->has('full_name') ? 'true' : 'false' }}">
    @error('full_name')<p class="error">{{ $message }}</p>@enderror
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email', $customer->email) }}" maxlength="255" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
    @error('email')<p class="error">{{ $message }}</p>@enderror
    <div class="actions"><button type="submit">Save customer</button><a href="{{ route('tfa7.customers.index') }}">Cancel</a></div>
</form>
@endsection
