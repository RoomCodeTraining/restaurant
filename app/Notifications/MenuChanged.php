<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MenuChanged extends Notification
{
    use Queueable;

    public $menu;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Un menu a été changé.')
                    ->line(sprintf('Le menu du %s a été changé.', $this->menu->served_at->format('d/m/Y')))
                    ->line('Veuillez vous connecter pour mettre à jour votre commande.')
                    ->action('Modifier ma commande', route('orders.index'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
