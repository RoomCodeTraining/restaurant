<?php

namespace App\Actions\Dish;

use App\Models\Dish;
use App\Events\UserCreated;
use Illuminate\Support\Facades\DB;

class CreateDishAction
{
    public function execute(array $data): Dish
    {
        DB::beginTransaction();

        $dish = Dish::create([
            'name' => $data['name'],
            'dish_type_id' => $data['dish_type_id'],
            'description' => $data['description'],
        ]);

        DB::commit();
        return $dish;
    }
}
