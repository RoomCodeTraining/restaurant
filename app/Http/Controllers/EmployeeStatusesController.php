<?php

namespace App\Http\Controllers;

use App\Models\EmployeeStatus;

class EmployeeStatusesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(EmployeeStatus::class, 'employeeStatus');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employee-statuses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee-statuses.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeStatus $employeeStatus)
    {
        return view('employee-statuses.edit', [
            'employeeStatus' => $employeeStatus
        ]);
    }
}
