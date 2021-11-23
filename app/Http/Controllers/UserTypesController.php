<?php

namespace App\Http\Controllers;

use App\Models\UserType;

class UserTypesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(UserType::class, 'userType');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user-types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user-types.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function edit(UserType $userType)
    {
        return view('user-types.edit', compact('userType'));
    }
}
