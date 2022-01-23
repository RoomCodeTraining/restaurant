<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\UpdateUserPassword;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('accessCard')->active()->get();

        $users = $users->filter(function ($user) {
            return !$user->isFromLunchroom();
        });

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' =>  ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:225'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
        ]);
        DB::beginTransaction();
        $user = auth()->user();
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact' => $request->contact,
        ]);
        DB::commit();

        return response()->json([
            "message" => 'Le compte a été mis à jour avec succès',
            "user" => new UserResource($user)
        ], 201);
    }

    public function changePassword(Request $request, UpdateUserPassword $changeUserPassword){
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8']
        ]);


   
        $passwordExpired = session()->pull('password_expired', false);
        $changeUserPassword->update(auth()->user(), $request->all(), $passwordExpired);

        return response()->json([
            "message" => 'Le mot de passe a été mis à jour avec succès',
        ], 201);
    }


    public function userAuthenticate(Request $request){
        return response()->json([
            "message" => "Information sur l'utilisateur connecté",
            "user" => new UserResource(Auth::user()),
        ]);
    }
}
