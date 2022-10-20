<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChargeUsers extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'charge:users';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Réduction du quota de déjeuner des utilisateurs ayant commandé un plat';

  /**
   * Débiter le quota lunch des utilisateurs qui ont une commande pour le jour en cours.
   *
   * @return int
   */
  public function handle()
  {

    Order::with('user.accessCard')->today()->whereState('state', Confirmed::class)->each(function (Order $order) {

      /*
        * Réduire le quota de déjeuner de l'utilisateur.
        */

      if (!$order->is_decrement && !$order->is_exceptional && $order->user->accessCard->quota_lunch > 0 && now()->hour >= (int) config('cantine.order.locked_at')) {
        $order->user->accessCard->decrement('quota_lunch');
        /*
            * Mise a jour de la methode de paiement ainsi que la access_card_id
            */
        $order->update([
          'payment_method_id' => $order->user->accessCard->payment_method_id,
          'access_card_id' => $order->user->accessCard->id,
          'is_decrement' => true,
          'new_quota_lunch' => $order->user->accessCard->quota_lunch,
        ]);

        activity()
          ->performedOn($order->user->accessCard)
          ->event("Le quota de Mr/Mme " . $order->user->full_name . " vient d" . "'être débité pour la commande du " . $order->menu->served_at->format('d-m-Y'))
          ->log('Debit de quota de déjeuner');
      }
      /*
        * Marquer la commande comme consommee.
        */
      //$order->markAsCompleted();
    });

    return Command::SUCCESS;
  }
}
