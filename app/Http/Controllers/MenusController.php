<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    public function index()
    {
        return view('menus.index');
    }

    public function create()
    {
        return view('menus.create');
    }

    public function show(Menu $menu)
    {
        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menus.index');
    }

}
