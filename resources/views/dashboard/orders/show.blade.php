@extends('layouts.dashboard')

@section('title', 'Order Details')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- ... Existing code ... -->

            <div class="order-details">
                <h6>Order Details:</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Shipping</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->payment_method }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->payment_status }}</td>
                            <td>{{ $order->shipping }}</td>
                            <td>{{ $order->tax }}</td>
                            <td>{{ $order->discount }}</td>
                            <td> ${{ $totalPrice }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if ($order->addresses)
                <div class="order-addresses">
                    <h6>Order Addresses:</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Street Address</th>
                                <th>City</th>
                                <th>Postal Code</th>
                                <th>State</th>
                                <th>Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->addresses as $address)
                                <tr>
                                    <td>{{ $address->type }}</td>
                                    <td>{{ $address->first_name }}</td>
                                    <td>{{ $address->last_name }}</td>
                                    <td>{{ $address->email }}</td>
                                    <td>{{ $address->phone_number }}</td>
                                    <td>{{ $address->street_address }}</td>
                                    <td>{{ $address->city }}</td>
                                    <td>{{ $address->postal_code }}</td>
                                    <td>{{ $address->state }}</td>
                                    <td>{{ $address->country }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No addresses found for this order.</p>
            @endif

            <div class="order-items">
                <h6>Order Items:</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    @if ($item->options)
                                        <ul>
                                            @foreach ($item->options as $option)
                                                <li>{{ $option }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
