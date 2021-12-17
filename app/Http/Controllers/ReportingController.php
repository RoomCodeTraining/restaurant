<?php

namespace App\Http\Controllers;

class ReportingController extends Controller
{
    public function __invoke()
    {
        return view('reporting.index');
    }
}
