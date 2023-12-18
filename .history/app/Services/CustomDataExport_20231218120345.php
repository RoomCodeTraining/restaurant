<?php

namespace App\Services;

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
    public Mouvment $record;
    public Present $present;

    public function __construct(public Collection $builder)
    {
        $this->employees = User::onlyEmployee()->get();
    }

    public function transform()
    {
        return $this->countEmployeePresentForeachReport();
    }



    public function countReport()
    {
        return $this->builder->count();
    }

    public function getPresentDays()
    {
        $presentDays = $this->builder->toQuery()->pluck('report_at')->all();
        $formattedDays = [];

        foreach ($presentDays as $date) {
            $formattedDate = date('l', strtotime($date));
            $formattedDays[] = $formattedDate;
        }

        return $formattedDays;
    }

    public function getPresentDate()
    {
        $presentDates = $this->builder->toQuery()->pluck('report_at')->all();
        return $presentDates;
    }

    /**********************************Calcule time********************************* */
    // public function getTime()
    // {


    //     // $users = DB::table('presents')
    //     //     ->join('report', 'presents.report_id', '=', 'report.id')
    //     //     ->join('users', 'presents.user_id', '=', 'users.id')
    //     //     ->select('users.*', 'report')
    //     //     ->get();

    //     // $first = DB::table('reports')->whereNotNull('report_at');

    //     // //dd($first);

    //     // $users = DB::table('report')
    //     //     ->whereNotNull('report_id')
    //     //     ->union($first)
    //     //     ->get();

    //     // dd($users);

    //     $report = $this->builder->toQuery()->first();

    //     $record = Mouvment::where('user_id', '!=', null)->whereDate('mouvment_at', $report->report_at)->first();

    //     $present = Present::where(['report_id' =>  $report->id, 'user_id' => $record->user_id])->first();
    //     $employeMouvmentService = new TransformMouvmentData($present);
    //     $dato = $employeMouvmentService->getTimePassedOnSite()->format('H:i:s');

    //     // dd($dato);
    //     // foreach ($this->employees as $employee) {
    //     //     $pre = $this->builder->toQuery()
    //     //         ->whereHas('presents', function (Builder $query) use ($employee) {
    //     //             $query->where('user_id', $employee->id);
    //     //         });

    //     //     $pre = $this->getTime();
    //     // }
    //     return $dato;
    // }
    /**************************************End Time************************* */

    /********************************************Mouvment***************************** */
    // public function countMouvmentEmployee()
    // {
    //     $entree = Mouvment::query()
    //         ->where([
    //             'user_id' => $this->employees->id,
    //         ])
    //         ->whereDate('mouvment_at', \Carbon\Carbon::parse($present->report->report_at))

    //         ->whereIn('access_point', ['Tourniquet Entrée', 'Barrière levante Entrée', 'Tourniquet Sortie', 'Barrière levante Sortie']);

    //     return $entree->count();
    // }
    /*******************************************END Mouvment******************************* */
    public function countEmployeePresentForeachReport()
    {
        // dd($this->countMouvmentEmployee($this->present));
        // dd($this->getTime());

        $report = $this->builder->toQuery()->first();
        $data = [];
        foreach ($this->employees as $employee) {
            $presentsCount = $this->builder->toQuery()
                ->whereHas('presents', function (Builder $query) use ($employee) {
                    $query->where('user_id', $employee->id);
                })
                ->count();


            // $entree = Mouvment::query()
            //     ->where([
            //         'user_id' => $employee->id,
            //     ])
            //     ->whereDate('mouvment_at', \Carbon\Carbon::parse($report->report_at))

            //     ->whereIn('access_point', ['Tourniquet Entrée', 'Barrière levante Entrée', 'Tourniquet Sortie', 'Barrière levante Sortie'])
            //     ->groupBy('mouvment_at')
            //     ->count();

            // dd($entree);
            // foreach ($entree as $key) {
            //     dd($key);
            // }
            // dd($entree->count());


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
