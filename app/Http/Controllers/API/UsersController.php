<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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


    public function updateProfile(Request $request, UpdateUserAction $action)
    {
        $request->validate([
            'first_name' =>  ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:225'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'contact' => ['required', 'string', 'max:255'],
        ]);

         $user = $action->execute(auth()->user(), $request->all());

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
}
