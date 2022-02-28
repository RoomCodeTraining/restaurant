<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use App\Support\ActivityHelper;
use Illuminate\Support\Facades\DB;

class CreateMenuAction
{
  public function execute(array $input): Menu
  {
    DB::beginTransaction();

    $menu = Menu::create([
      'served_at' => $input['served_at'],
    ]);

    /*
    *   Log the activity
    */
    ActivityHelper::createActivity(
      $menu,
      'Creation de nouveau menu',
      'Ajout de menu du ' . \Carbon\Carbon::parse($this->state['served_at'])->format('d-m-Y'),
    );


    $dishes = [
      $input['starter_id'],
      $input['main_dish_id'],
      $input['second_dish_id'],
      $input['dessert_id'],
    ];

    $menu->dishes()->attach(collect($dishes)->filter(fn ($dish) => $dish));

    DB::commit();

    return $menu;
  }
}
