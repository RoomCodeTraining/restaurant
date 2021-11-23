<?php

namespace App\Http\Controllers;

class OrdersSummaryController extends Controller
{
    public function __invoke()
    {
        return view('orders.summary.index');
    }
}
