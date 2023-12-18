<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CustomDataExport
{

    public Collection $employees;
    public Menu $record;
    public Order $order;

    public function __construct(public Collection $builder)
    {

        $this->employees = Order::all();
    }

    public function transform()
    {
        return $this->countEmployeePresentForeachReport();
    }

    public function countReport()
    {

        return $this->builder->count();
    }

    public function countEmployeePresentForeachReport()
    {
        // dd($this->countMouvmentEmployee($this->present));
        // dd($this->getTime());

        $report = $this->builder->toQuery()->first();
        // dd($report);
        $data = [];
        foreach ($this->employees as $employee) {
            $p = $this->employees->toQuery()->whereHas('menu', function (Builder $query) use ($employee) {
                $query->where('user_id', $employee->user_id)->where('menu_id', $employee->menu_id);
            });

            //dd($p);
            // $presentsCount = $this->builder->toQuery()
            //     ->whereHas('dishes', function (Builder $query) use ($employee) {
            //         $query->where('user_id', $employee->id);
            //     })
            //     ->count();

            // dd($presentsCount);
            //dd($employee);
            $employeeData = [
                'full_name' => $employee->full_name,
                'department' => $employee->department?->name,
                'organization' => $employee->organization?->name,
                'menu' => $p->menu->name,

                // 'nbre_mouvment' => $entree->count(),
                // 'ok' => $this->getTime(),
                //'present_count' => $presentsCount . '/' . $this->countReport(),

            ];

            // dd($employee);
            $data[] = $employeeData;
        }

        return collect($data);
    }
}
