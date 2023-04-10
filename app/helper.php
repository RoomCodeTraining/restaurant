<?php

use App\Models\Order;
use App\Models\User;
use Filament\Notifications\Notification;

if (! function_exists('dishName')) {
    function dishName($dishId): string
    {
        return \App\Models\Dish::whereId($dishId)->first()->name;
    }
}

if(! function_exists('authCard')) {
    function authCard()
    {
        if(auth()->check()) {
            return auth()->user()->currentAccessCard;
        }
    }
}

if(! function_exists('hasAlreadyEteanTodayBreakfast')) {
    function hasAlreadyEteanTodayBreakfast(User $user) : bool
    {
        return Order::where('user_id', $user->id)->withoutGlobalScopes()->where('type', 'breakfast')->whereDate('created_at', today())->exists()
                      ? true
                     : false;
    }
}

/*
* Helper pour la notification
*/
if(! function_exists('flasher')) {
    function flasher(string $type, string $message)
    {
        Notification::make()->success()->title($message)->iconColor($type)->send();
    }
}
