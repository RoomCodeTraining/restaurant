<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::with('role')->where('email', $request->email)->orWhere('username', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['E-mail ou mot de passe incorrect']
            ], 404);
        }

        $token = $user->createToken('bearer-token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
}
