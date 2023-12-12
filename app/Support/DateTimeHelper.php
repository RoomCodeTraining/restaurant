<?php

namespace App\Support;

class DateTimeHelper
{
    public static function inThePeriod($value): array
    {
        $begin = now();
        $end = now();

        switch ($value) {
            case 'today':
                $begin->startOfDay();
                $end->endOfDay();

                break;
            case 'yesterday':
                $begin->subDay()->startOfDay();
                $end->subDay()->endOfDay();

                break;
            case 'tomorrow':
                $begin->addDay()->startOfDay();
                $end->addDay()->endOfDay();

                break;
            case 'last_month':
                $begin->subMonth()->startOfMonth();
                $end->subMonth()->endOfMonth();

                break;
            case 'this_month':
                $begin->startOfMonth();
                $end->endOfMonth();

                break;
            case 'next_month':
                $begin->addMonth()->startOfMonth();
                $end->addMonth()->endOfMonth();

                break;
            case 'last_year':
                $begin->subYear()->startOfYear();
                $end->subYear()->endOfYear();

                break;
            case 'this_year':
                $begin->startOfYear();
                $end->endOfYear();

                break;
            case 'next_year':
                $begin->addYear()->startOfYear();
                $end->addYear()->endOfYear();

                break;
            case 'this_week':
                $begin->startOfWeek();
                $end->endOfWeek();

                break;
            case 'last_week':
                $begin->subWeek()->startOfWeek();
                $end->subWeek()->endOfWeek();

                break;
            case 'this_quarter':
                $begin->startOfMonth();
                $end->addMonths(2)->endOfMonth();

                break;
            case 'last_quarter':
                $begin->subMonths(3)->startOfMonth();
                $end->subMonth()->endOfMonth();

                break;
            default:
                break;
        }

        return [$begin, $end];
    }


    public static function getPeriod() : array
    {
        return [
            'today' => 'Aujourd\'hui',
            'yesterday' => 'Hier',
            'this_week' => 'Cette semaine',
            'last_week' => 'La semaine dernière',
            'this_month' => 'Ce mois-ci',
            'last_month' => 'Le mois dernier',
            'this_year' => 'Cette année',
            'last_year' => 'L\'année dernière',
            'this_quarter' => 'Ce trimestre',
            'last_quarter' => 'Le trimestre dernier',
        ];
    }
}