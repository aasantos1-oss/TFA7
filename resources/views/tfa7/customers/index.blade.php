@extends('tfa7.layout')
@section('title', 'Customers')
@section('content')
<div class="heading"><h1>Customers</h1><a class="button" href="{{ route('tfa7.customers.create') }}">Create customer</a></div>
<div class="panel table-wrap"><table><thead><tr><th>ID</th><th>Full name</th><th>Email</th><th>Action</th></tr></thead><tbody>
@forelse ($customers as $customer)
<tr><td>{{ $customer->id }}</td><td>{{ $customer->full_name }}</td><td>{{ $customer->email }}</td><td><a href="{{ route('tfa7.customers.edit', $customer) }}">Edit</a></td></tr>
@empty<tr><td colspan="4">No customers yet.</td></tr>@endforelse
</tbody></table></div>
@include('tfa7.pagination', ['records' => $customers])
@endsection
