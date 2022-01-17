<?php


if (!function_exists('dishName')) {
    function dishName($dishId): string
    {
        return \App\Models\Dish::whereId($dishId)->first()->name;
    }
}
