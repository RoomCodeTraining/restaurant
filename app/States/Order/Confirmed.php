<?php

namespace App\States\Order;

class Confirmed extends OrderState
{
    public static $name = 'confirmed';

    public static function title(): string
    {
        return 'Confirmée';
    }

    public static function color(): string
    {
        return 'badge badge-warning';
    }
}
