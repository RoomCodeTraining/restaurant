<?php

namespace App\Actions\Dish;

use App\Models\Dish;
use App\Events\UserUpdated;
use Illuminate\Support\Facades\DB;

class UpdateDishAction
{
    public function execute(Dish $dish, array $data)
    {
        DB::beginTransaction();

        $dish->update([
           'name' => $data['name'],
            'dish_type_id' => $data['dish_type_id'],
            'description' => $data['description'],
        ]);

      

        DB::commit();
        return $dish->fresh();
    }
}
