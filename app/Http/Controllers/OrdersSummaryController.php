<?php

namespace App\Http\Controllers;

class OrdersSummaryController extends Controller
{
    public function weeklyOrder()
    {
        return view('orders.summary.index');
    }

    public function todayOrder(){
        return view('orders.summary.today');
    }
}
