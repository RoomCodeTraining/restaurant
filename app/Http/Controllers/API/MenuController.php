<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
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
        return MenuResource::collection(Menu::with('starterDish', 'mainDish', 'secondDish', 'dessertDish')->orderByDesc('created_at')->take(6)->get());
    }



    

  

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        $menu = Menu::whereId($menu->id)->with('starterDish', 'mainDish', 'secondDish', 'dessertDish')->first();
        return new MenuResource($menu);
    }

 

 
}
