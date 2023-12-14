<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;

class RolesController extends Controller
{
    public function __construct()
    {
        // $this->authorize('manage', User::class);
    }

    public function index()
    {
        return view('roles.index');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', [
            'role' => $role->load('permissions'),
        ]);
    }

    public function show(Role $role)
    {
        return view('roles.show', [
            'role' => $role->load('permissions'),
        ]);
    }
}
