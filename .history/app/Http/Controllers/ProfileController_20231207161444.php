<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function show()
    {
        return view('account.profile', [
            'user' => auth()->user(),
        ]);
    }
}
