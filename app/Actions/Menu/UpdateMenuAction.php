<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use App\States\Order\Confirmed;
use App\States\Order\Suspended;
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

        // Move to a new action
        $menu->orders()
            ->whereState('state', Confirmed::class)
            ->get()
            ->each(fn ($order) => $order->state->transitionTo(Suspended::class));

        DB::commit();

        return $menu->fresh();
    }
}
