<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use App\Models\Report;
use App\Models\Present;
use App\Models\Mouvment;
use Illuminate\Support\Facades\DB;
use App\Services\TransformMouvmentData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CustomExportDataService
{

    public Collection $employees;
    public Menu $record;
    //public Present $present;

    public function __construct(public Collection $builder)
    {
        $this->employees = User::all();
    }

    public function transform()
    {
        return $this->countEmployeePresentForeachReport();
    }



    public function countReport()
    {
        return $this->builder->count();
    }

    // public function getPresentDays()
    // {
    //     $presentDays = $this->builder->toQuery()->pluck('report_at')->all();
    //     $formattedDays = [];

    //     foreach ($presentDays as $date) {
    //         $formattedDate = date('l', strtotime($date));
    //         $formattedDays[] = $formattedDate;
    //     }

    //     return $formattedDays;
    // }

    // public function getPresentDate()
    // {
    //     $presentDates = $this->builder->toQuery()->pluck('report_at')->all();
    //     return $presentDates;
    // }

    public function countEmployeePresentForeachReport()
    {
        // dd($this->countMouvmentEmployee($this->present));
        // dd($this->getTime());

        $report = $this->builder->toQuery()->first();
        dd($report);
        $data = [];
        foreach ($this->employees as $employee) {
            $presentsCount = $this->builder->toQuery()
                ->whereHas('presents', function (Builder $query) use ($employee) {
                    $query->where('user_id', $employee->id);
                })
                ->count();

            $employeeData = [
                'full_name' => $employee->full_name,
                'department' => $employee->department?->name,
                'organization' => $employee->organization?->name,
                'date_presence' => $this->getPresentdate(),
                'jour_presence' => $this->getPresentDays(),
                // 'nbre_mouvment' => $entree->count(),
                // 'ok' => $this->getTime(),
                'present_count' => $presentsCount . '/' . $this->countReport(),

            ];

            $data[] = $employeeData;
        }

        return collect($data);
    }
}
