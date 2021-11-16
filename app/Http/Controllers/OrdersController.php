<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersController extends Controller
{
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

    public function edit()
    {
        return view('orders.edit');
    }
}