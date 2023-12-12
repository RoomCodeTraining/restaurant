<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuSpecal;
use App\Models\MenuSpecial;
use Illuminate\Http\Request;

class MenuSpecalController extends Controller
{
    public function index()
    {
        return view('menus.test.index');
    }

    public function create()
    {
        $this->authorize('create', Menu::class);
        return view('menus.specials.create');
    }

    public function edit($menuSpecials)
    {
        $this->authorize('manage', Menu::class);
        $menuSpecials = MenuSpecial::findOrFail($menuSpecials);
        return view('menus.specials.edit', compact('menuSpecials'));
    }

    public function show(MenuSpecial $menuSpecial)
    {
        $this->authorize('manage', Menu::class);
        return view('menus.specials.show', compact('menuSpecial'));
    }
}
