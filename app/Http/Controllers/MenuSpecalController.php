<?php

namespace App\Http\Controllers;

use App\Models\MenuSpecal;
use Illuminate\Http\Request;

class MenuSpecalController extends Controller
{
    public function index()
    {
        return view('menus.specials.index');
    }

    public function create()
    {
        $this->authorize('create', Menu::class);
        return view('menus.specials.create');
    }

    public function edit($menuSpecial)
    {
        $this->authorize('manage', Menu::class);
        $menuSpecial = MenuSpecal::findOrFail($menuSpecial);
        return view('menus.specials.edit', compact('menuSpecial'));
    }

    public function show(MenuSpecal $menuSpecial)
    {
        $this->authorize('manage', Menu::class);
        return view('menus.specials.show', compact('menuSpecial'));
    }

}
