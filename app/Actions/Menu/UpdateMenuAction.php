<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use App\States\Order\Confirmed;
use App\States\Order\Suspended;
use App\Support\ActivityHelper;
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

    $menu->dishes()->sync(collect($dishes)->filter(fn ($dish) => $dish));

    // Move to a new action
    $menu->orders()
      ->whereState('state', Confirmed::class)
      ->get()
      ->each(fn ($order) => $order->state->transitionTo(Suspended::class));
    /*
     *   Log the activity
    */
    ActivityHelper::createActivity(
      $menu,
      'Modification du menu du ' . \Carbon\Carbon::parse($menu->served_at)->format('d-m-Y'),
      'Modification de menu',
    );
    DB::commit();

    return $menu->fresh();
  }
}
