<?php

namespace App\Actions\Dish;

use App\Models\Dish;
use App\Support\ActivityHelper;
use Illuminate\Validation\ValidationException;

final class DeleteDishAction
{
  public function execute(Dish $dish): Dish
  {
    if (null !== $dish->deleted_at) {
      throw_if(null !== $dish->deleted_at, ValidationException::withMessages([
        'delete_dish' => 'Ce plat est deja supprimé.',
      ]));
    }

    /*
    *   Log the activity
    */
    ActivityHelper::createActivity(
      $dish,
      'Suppression du plat ' . $dish->name,
      'Suppresion de plat',
    );
    $dish->delete();




    // dishDeleted::dispatch($dish);

    return $dish;
  }
}
