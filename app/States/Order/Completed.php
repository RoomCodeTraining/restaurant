<?php

namespace App\States\Order;

class Completed extends OrderState
{
    public static $name = 'completed';

    public static function title(): string
    {
        return 'Consommée';
    }

    public static function color(): string
    {
        return 'badge badge-success';
    }
}
