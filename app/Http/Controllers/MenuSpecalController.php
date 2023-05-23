<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuSpecalController extends Controller
{
    public function index()
    {
        return view('menus.specials.index');
    }

    public function create()
    {
        return view('menus.specials.create');
    }

    public function edit(MenuSpecal $menuSpecal)
    {
        return view('menus.specials.edit', compact('menuSpecal'));
    }

    public function show(MenuSpecal $menuSpecal)
    {
        return view('menus.specials.show', compact('menuSpecal'));
    }

}
