<?php

namespace App\States\Order;

class Cancelled extends OrderState
{
    public static $name = 'cancelled';

    public static function title(): string
    {
        return 'Annulée';
    }

    public static function description(): string
    {
        return 'Annulée';
    }
}