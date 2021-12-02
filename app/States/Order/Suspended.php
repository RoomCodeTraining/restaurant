<?php

namespace App\States\Order;

class Suspended extends OrderState
{
    public static $name = 'suspended';

    public static function title(): string
    {
        return 'Suspendu';
    }
}
