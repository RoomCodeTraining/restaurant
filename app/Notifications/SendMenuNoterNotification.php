<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMenuNoterNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Bonjour ' . $this->order->user->name)
                    ->subject('Noter le menu de la veille')
                    ->line('Vous avez consommé le menu de la veille, veuillez noter le menu de la veille en cliquant sur le bouton ci-dessous.')
                    ->action('Noter le menu de la veille', route('menus.rating', $this->order))
                    ->salutation('Cordialement, ' . config('app.name') . ' !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
