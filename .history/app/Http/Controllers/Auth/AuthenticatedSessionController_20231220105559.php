<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use App\Support\ActivityHelper;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        if (auth()->user()->hasRole(Role::OPERATOR_LUNCHROOM)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        ActivityHelper::createActivity(
            auth()->user(),
            'Nouvelle connexion',
            "Connexion a  la plateforme",
        );

        Notification::make()->title("Nouvelle connexion")->body('Bienvenue ' . auth()->user()->full_name)->success()->send();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {

        ActivityHelper::createActivity(
            auth()->user(),
            'Deconnexion',
            "Deconnexion de la plateforme",
        );

        $auth = Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Notification::make()->title("Deconnexion")->body('Vous avez été déconnecté avec succès')->success()->send();

        return redirect('/');
    }
}
