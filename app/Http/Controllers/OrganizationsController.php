<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class, 'organization');
    }

    public function index()
    {
        return view('organizations.index');
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }
}
