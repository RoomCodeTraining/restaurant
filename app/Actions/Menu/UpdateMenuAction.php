<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class UpdateMenuAction
{
    public function execute(Menu $menu, array $input)
    {
        DB::beginTransaction();

        $dishes = [
            $input['starter_id'],
            $input['main_dish_id'],
            $input['second_dish_id'],
            $input['dessert_id'],
        ];

        $menu->dishes()->sync($dishes);

        DB::commit();

        return $menu->fresh();
    }
}
