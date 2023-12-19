<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckPasswordValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_numeric(config('cantine.password_expires_days'))) {
            $passwordChangedAt = new Carbon($request->user()->password_changed_at ?? $request->user()->created_at);

            if (now()->diffInDays($passwordChangedAt) >= config('cantine.password_expires_days')) {

                Notification::make()
                ->title("Mot de passe expiré")
                ->body("Votre mot de passe a expiré. Nous vous demandons de changer votre mot de passe tous les ".config('cantine.password_expires_days') ." jours pour des raisons de sécurité.")
                ->warning()->send(auth()->user());


                session(['password_expired' => true]);

                return redirect()->route('profile');
            }
        }

        return $next($request);
    }

}