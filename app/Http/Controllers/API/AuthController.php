<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityHelper;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\Activitylog\Exceptions\CouldNotLogActivity;

class AuthController extends Controller
{
    /**
     * Authentifier un utilisateur
     *
     * Cette endpoint permet d'authentifier un utilisateur7
     * @group Authentification
     * @param Request $request
     * @return Response|ResponseFactory
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws CouldNotLogActivity
     * @throws InvalidCastException
     * @throws ClassMorphViolationException
     */
    public function login(Request $request)
    {
        $user = User::with('role')
            ->where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(
                [
                    'message' => ['E-mail ou mot de passe incorrect'],
                ],
                404,
            );
        }

        $token = $user->createToken('bearer-token')->plainTextToken;

        ActivityHelper::createActivity($user, 'Connexion a l\'application mobile', 'Nouvelle connexion');

        return response([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Déconnecter un utilisateur
     *
     * Cette endpoint permet de déconnecter un utilisateur
     * @authenticated
     * @group Authentification
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->responseDeleted('Utilisateur déconnecté');
    }
}
