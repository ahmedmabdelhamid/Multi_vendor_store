
@extends('layouts.dashboard')

@section('title', 'Orders')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')


<x-alert type="success" />
<x-alert type="info" />

<form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
    <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
    <select name="status" class="form-control mx-2">
        <option value="">All</option>
        <option value="active" @selected(request('status') == 'active')>Active</option>
        <option value="archived" @selected(request('status') == 'archived')>Archived</option>
    </select>
    <button class="btn btn-dark mx-2">Filter</button>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Payment Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->payment_method }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->payment_status }}</td>
            <td><a href="{{ route('dashboard.orders.show', $order->id) }}" class="btn btn-sm btn-outline-success">
                Order {{ $order['order_id'] }} Details
            </a>
            </td>

        </tr>
    @endforeach
    </tbody>
</table>


@endsection
