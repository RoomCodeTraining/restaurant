<?php

namespace App\States\Order;

class Confirmed extends OrderState
{
    public static $name = 'confirmed';

    public static function title(): string
    {
        return 'Commande effectuée';
    }

    public static function description(): string
    {
        return 'Non consommée';
    }
}
