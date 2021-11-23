<?php

namespace App\Actions\Dish;

use App\Models\Dish;
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

        $dish->delete();

        // dishDeleted::dispatch($dish);

        return $dish;
    }
}
