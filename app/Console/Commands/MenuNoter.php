<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\SendMenuNoterNotification;
use App\States\Order\Completed;
use Illuminate\Console\Command;

class MenuNoter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:noter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Noter le menu de la veille';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des collaborateurs ayant commandé un plat et qui ont consommé le menu de la veille');

        $orders = Order::whereHas('menu', function ($query) {
            $query->where('served_at', now()->subDay()->format('Y-m-d'));
        })->whereState('state', Completed::class)->get();

        $this->info('Nombre de collaborateurs ayant commandé un plat et qui ont consommé le menu de la veille : ' . $orders->count());

        if($orders->count() > 0) {

            $this->info('Envoi de la notification aux collaborateurs ayant commandé un plat et qui ont consommé le menu de la veille');

            $orders->each(function (Order $order) {
                $order->user->notify(new SendMenuNoterNotification($order));
            });
        }

        $this->info('Les collaborateurs ayant commandé un plat ont été notifié avec succès');
    }
}
