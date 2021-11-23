<?php

namespace App\Actions\Menu;

use App\Models\Menu;
use Illuminate\Validation\ValidationException;

final class DeleteMenuAction
{
    public function execute(Menu $menu): Menu
    {
        if (null !== $menu->deleted_at) {
            throw_if(null !== $menu->deleted_at, ValidationException::withMessages([
                'delete_menu' => 'Ce menu est deja supprimé.',
            ]));
        }

        $menu->delete();

        // dishDeleted::dispatch($dish);

        return $menu;
    }
}
