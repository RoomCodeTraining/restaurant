<?php

namespace App\TableFilters;

use App\Models\Department;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DepartmentFilter
{
    public static $title = "Départements";
    public static $id = 'department';

    public static function make()
    {
        $departments = Department::pluck('name', 'id')->toArray();

        return Filter::make(self::$title)->select(['' => 'Tous'] + $departments);
    }
}
