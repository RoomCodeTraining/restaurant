<?php

namespace App\Http\Controllers;

use App\Models\Dish;
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
        $dishes = Dish::pluck('name', 'id');
        return view('menus.create', compact('dishes'));
    }

    public function show(Menu $menu){
        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu){
        return view('menus.edit', compact('menu'));
    }
}
