<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DishResource;
use App\Models\Menu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DishesController
{
    use AuthorizesRequests;

    public function index()
    {
        //$this->authorize('viewAny', Menu::class);

        $todayMenu = Menu::query()->whereDate('served_at', today())->first();

        if (!$todayMenu) {
            return response()->json([
                'message' => 'Aucun menu n\'est disponible pour aujourd\'hui.',
                'success' => false,
            ], 404);
        }

        $dishes = $todayMenu->mainDishes()->get();

        return DishResource::collection($dishes);
    }
}
