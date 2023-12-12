<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuSpecial;

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
        $menuSpecial = MenuSpecial::findOrFail($menuSpecial);

        //dd($menuSpecial);
        return view('menus.specials.edit', compact('menuSpecial'));
    }

    public function show(MenuSpecial $menuSpecial)
    {
        $this->authorize('manage', Menu::class);

        return view('menus.specials.show', compact('menuSpecial'));
    }
}