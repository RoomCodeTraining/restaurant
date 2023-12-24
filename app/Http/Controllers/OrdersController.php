<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    public function index()
    {
        return view('orders.index');
    }

    public function create()
    {
        return view('orders.create');
    }

    public function show()
    {
        return view('orders.show');
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function noter(Order $order)
    {
        return view('orders.rating', compact('order'));
    }

}
