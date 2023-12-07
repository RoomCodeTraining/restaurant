<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {

        // $user = Auth::user()->accessCards;
        // dd($user);
        return view('account.profile', [
            'user' => auth()->user(),
        ]);
    }
}
