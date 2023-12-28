<?php

namespace App\Actions\Dish;

use App\Models\Dish;
use Illuminate\Support\Facades\DB;

class UpdateDishAction
{
    public function execute(Dish $dish, array $data)
    {
        DB::beginTransaction();

        if(count($data['image_path']) > 0) {
            foreach($data['image_path'] as $img) {
                $data['image_path'] = store_dish_image($data['image_path']);
            }
        } else {
            $data['image_path'] = $dish->image_path;
        }

        $dish->update([
           'name' => $data['name'],
            'dish_type_id' => $data['dish_type_id'],
            'description' => $data['description'],
            'image_path' => $data['image_path'],
        ]);



        DB::commit();

        return $dish->fresh();
    }
}