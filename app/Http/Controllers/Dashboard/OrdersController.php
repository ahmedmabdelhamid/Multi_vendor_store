<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        return view('dashboard.orders.index', compact('orders'));



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(Order $order)
    // {
    //     return view('dashboard.orders.show', compact('order'));
    // }
    public function show(Order $order)
    {
        // Load related addresses and items
        $order->load('addresses', 'items');

        // Calculate the total price
        $totalPrice = $order->shipping + $order->tax - $order->discount + $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('dashboard.orders.show', compact('order', 'totalPrice'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
