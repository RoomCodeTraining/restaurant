<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::with('starterDish', 'mainDish', 'secondDish', 'dessertDish')
            ->whereBetween('served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        return MenuResource::collection($menus);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        return new MenuResource($menu->load('starterDish', 'mainDish', 'secondDish', 'dessertDish'));
    }
}
