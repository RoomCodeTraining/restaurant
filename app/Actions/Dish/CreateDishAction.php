<?php

namespace App\Actions\Dish;

use App\Models\Dish;
use App\Support\ActivityHelper;
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
      'image_path' => $data['image_path'] ?? null,
    ]);

    /*
    *   Log the activity
    */
    ActivityHelper::createActivity(
      $dish,
      'Creation du plat ' .$dish->name,
      'Ajout de nouveau plat',
    );

    DB::commit();

    return $dish;
  }
}
