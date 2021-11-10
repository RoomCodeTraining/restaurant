<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index()
    {
        return view('commande.index');
    }

    public function create()
    {

        return view('commande.create');
    }

    public function show(){
        return view('commande.show');
    }

    public function edit(){
        return view('commande.edit');
    }
}
