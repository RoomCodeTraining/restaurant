<?php

namespace App\States\Order;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderState extends State
{
    abstract public static function title(): string;

    abstract public static function description(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Confirmed::class)
            ->allowTransition(Confirmed::class, Completed::class)
            ->allowTransition(Confirmed::class, Cancelled::class)
            ->allowTransition(Confirmed::class, Suspended::class)
            ->allowTransition(Suspended::class, Confirmed::class)
            ->allowTransition(Suspended::class, Cancelled::class)
        ;
    }
}
