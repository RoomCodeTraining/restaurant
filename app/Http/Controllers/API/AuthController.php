<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Support\ActivityHelper;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     *  Nouvelle connexion
     *
     * Cette endpoint permet de se connecter à l'application mobile
     * @param Request $request
     * @return Response|ResponseFactory
     */
    public function login(Request $request)
    {
        $user = User::with('role')
            ->where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->responseError(__('Identifiants incorrects'));
        }

        ActivityHelper::createActivity($user, 'Connexion a l\'application mobile', 'Nouvelle connexion');

        return $this->responseSuccess(__('Nouvelle connexion'), [
            'user' => new UserResource($user),
            'token' => $user->createToken('bearer-token')->plainTextToken,
        ]);
    }
}