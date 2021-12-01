<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\WelcomeNotification\WelcomeNotification;

class WelcomeMessageController extends WelcomeNotification
{
    public function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject('Création de votre compte!')
            ->line('votre compte a été crée sur la plateforme Ciprel cantine.')
            ->line('Veuillez cliquer sur le lien ci dessous afin de renseigner votre mot de passe.')
            ->action('Renseigner votre mot de passe', $this->showWelcomeFormUrl);
    }
}
