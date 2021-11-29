<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class CreateMenuAction
{
    public function execute(array $input): Menu
    {
        DB::beginTransaction();

        $menu = Menu::create([
            'served_at' => $input['served_at'],
        ]);

        $dishes = [
            $input['starter_id'],
            $input['main_dish_id'],
            $input['second_dish_id'],
            $input['dessert_id'],
        ];

        $menu->dishes()->attach($dishes);

        DB::commit();

        return $menu;
    }
}
