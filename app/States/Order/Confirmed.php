<?php

namespace App\States\Order;

class Confirmed extends OrderState
{
    public static $name = 'confirmed';

    public static function title(): string
    {
        return 'Confirmée';
    }
}
