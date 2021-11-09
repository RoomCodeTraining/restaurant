<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use App\Events\UserUpdated;
use Illuminate\Support\Facades\DB;

class UpdateMenuAction
{
    public function execute(Menu $menu, array $data)
    {
        DB::beginTransaction();

        $menu->update([
            'main_dish_id' => $data['main_dish_id'],
            'dessert_id' => $data['dessert_id'],
            'starter_dish_id' => $data['starter_dish_id'],
            'second_dish_id' => $data['second_dish_id'],
        ]);

        DB::commit();

        return $menu->fresh();
    }
}
