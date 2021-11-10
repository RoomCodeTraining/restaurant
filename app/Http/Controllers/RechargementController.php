<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RechargementController extends Controller
{
    public function index()
    {
        return view('rechargement.index');
    }

    public function create()
    {

        return view('rechargement.create');
    }

    public function show(){
        return view('rechargement.show');
    }

    public function edit(){
        return view('rechargement.edit');
    }
}
