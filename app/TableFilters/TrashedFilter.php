<?php

namespace App\TableFilters;

use Rappasoft\LaravelLivewireTables\Views\Filter;


class TrashedFilter
{
    public static $title = "Statut";
    public static $id = 'status';

    public static function make()
    {
        return Filter::make(self::$title)->select([
            '' => 'Tous',
            'with_trashed' => 'Avec supprimés',
            'trashed' => 'Seulement supprimés',
        ]);
    }
}
