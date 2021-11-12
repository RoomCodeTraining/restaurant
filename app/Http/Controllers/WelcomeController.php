<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
use Spatie\WelcomeNotification\WelcomeController as BaseWelcomeController;

class WelcomeController extends BaseWelcomeController
{
    public function sendPasswordSavedResponse(): Response
    {
        return redirect()->route('dashboard');
    }

    protected function rules()
    {
        return [
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ];
    }
}
