<?php

namespace App\Http\Controllers\API;

use App\Actions\User\UpdateUserPassword;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @group Gestion des utilisateurs
 * @authenticated
 * APIs for managing users
 */
class UsersController extends Controller
{
    /**
     * Lister les utilisateurs de l'application
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('accessCard', 'role', 'organization')
            ->active()
            ->get();

        $users = $users->filter(function ($user) {
            return ! $user->isFromLunchroom();
        });

        return UserResource::collection($users);
    }

    /**
     * Afficher les informations d'un utilisateur
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return  $this->responseSuccess('Détails utilisateur', new UserResource($user));
    }

    /**
     * Mettre à jour le profil de l'utilisateur connecté
     *
     * Cette endpoint permet de mettre à jour le profil de l'utilisateur connecté
     *
     * @bodyParam first_name string required Le prénom de l'utilisateur
     * @bodyParam last_name string required Le nom de l'utilisateur
     * @bodyParam email string required L'adresse email de l'utilisateur
     * @bodyParam contact string required Le contact de l'utilisateur
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
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

        return response()->json(
            [
                'message' => 'Le compte a été mis à jour avec succès',
                'user' => new UserResource($user),
            ],
            201,
        );
    }

    /**
     * Changer le mot de passe de l'utilisateur connecté
     * @bodyParam current_password string required Le mot de passe actuel de l'utilisateur
     * @bodyParam password string required Le nouveau mot de passe de l'utilisateur
     * @bodyParam password_confirmation string required La confirmation du nouveau mot de passe de l'utilisateur
     *
     * Cette endpoint permet de changer le mot de passe de l'utilisateur connecté
     * @param Request $request
     * @param UpdateUserPassword $changeUserPassword
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function changePassword(Request $request, UpdateUserPassword $changeUserPassword)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ]);

        $passwordExpired = session()->pull('password_expired', false);
        $changeUserPassword->update(auth()->user(), $request->all(), $passwordExpired);

        return response()->json(
            [
                'message' => 'Le mot de passe a été mis à jour avec succès',
            ],
            201,
        );
    }

    /**
     * Récupérer l'utilisateur connecté
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userAuthenticate(Request $request)
    {
        return $this->responseSuccess('Utilisateur connecté', new UserResource($request->user()));
    }
}
