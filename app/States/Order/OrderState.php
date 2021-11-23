<?php

namespace App\States\Order;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderState extends State
{
    abstract public static function title(): string;

    abstract public static function color(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Confirmed::class)
            ->allowTransition(Confirmed::class, Completed::class)
            ->allowTransition(Confirmed::class, Cancelled::class)
        ;
    }
}
