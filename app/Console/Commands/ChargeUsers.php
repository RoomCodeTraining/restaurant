<?php

namespace App\Console\Commands;

use App\Models\Order;
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
      DB::transaction(function () use ($order) {
        if ($order->user->accessCard->quota_lunch > 0) {
          $order->user->accessCard->decrement('quota_lunch');
          $order->update([
            'payment_method_id' => $order->user->accessCard->payment_method_id,
            'access_card_id' => $order->user->accessCard->id,
          ]);
        }
      });
    });

    return Command::SUCCESS;
  }
}
