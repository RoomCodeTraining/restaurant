<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Menu;

/**
 * @group Gestion des menus
 *
 * Endpoints pour la gestion des menus de la cantine
 * @authenticated
 * @package App\Http\Controllers\API
 */
class MenusController extends Controller
{
    /**
     * Recuperation du menu du jour
     *
     * Cette endpoint permet de récupérer le menu du jour
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::query()
            ->with('mainDishes')
            ->today()
            ->get();

        return MenuResource::collection($menus);
    }
}
