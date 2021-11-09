<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use App\Events\UserCreated;
use Illuminate\Support\Facades\DB;

class CreateMenuAction
{
    public function execute(array $data): Menu
    {
        DB::beginTransaction();

        $menu = Menu::create([
            'main_dish_id' => $data['main_dish_id'],
            'starter_dish_id' => $data['starter_dish_id'],
            'dessert_id' => $data['dessert_id'],
            'second_dish_id' => $data['second_dish_id'] ?? null,
            'served_at' => $data['served_at'],
        ]);

        DB::commit();
        return $menu;
    }
}
