<?php

namespace App\Http\Middleware;

use Closure;
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
                session()->flash(
                    'warning',
                    __(
                        "Votre mot de passe a expiré. Nous vous demandons de changer votre mot de passe tous les :jours jours pour des raisons de sécurité.",
                        ['jours' => config('cantine.password_expires_days')]
                    )
                );

                session(['password_expired' => true]);

                return redirect()->route('profile');
            }
        }

        return $next($request);
    }
}
