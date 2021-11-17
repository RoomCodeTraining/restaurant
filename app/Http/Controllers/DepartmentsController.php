<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Policies\DepartmentPolicy;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(DepartmentPolicy::class, 'department');
    }

    public function index()
    {
        return view('departments.index');
    }

    public function create()
    {
        return view('departments.create');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }
}
