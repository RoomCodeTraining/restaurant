<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function show()
    {

        $user = Auth::user()->with('accessCards')->latest();
        return view('account.profile', [
            'user' => auth()->user(),
        ]);
    }
}
