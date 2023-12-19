<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityHelper;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $p = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
                function ($value, $attribute, $fail) use ($request) {
                    $latestPassword = User::firstWhere('email', $request->email)
                        ->passwordHistories()
                        ->latest()
                        ->take(3)
                        ->get();
                    if ($latestPassword->count() > 0) {
                        foreach ($latestPassword as $password) {
                            if (Hash::check($value, $password->password)) {
                                $fail('Le mot de passe ne doit pas être identique aux 3 derniers.');
                            }
                        }
                    }
                },
            ],
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ]);

        Notification::make()
            ->title('Mot de passe modifié')
            ->body('Votre mot de passe a été modifié avec succès.')
            ->success()
            ->send();

        ActivityHelper::createActivity($user, 'Mis à jour de mot de passe', "L'utilisateur {$user->full_name} a mis à jour son mot de passe.");

        return redirect()->route('login');
    }
}