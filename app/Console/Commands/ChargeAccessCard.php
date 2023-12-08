<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChargeAccessCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charge:access-card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decrementation des quotas en attente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Order::weekly()->where('is_decrement', false)->latest()->whereState('state', [Confirmed::class, Completed::class])
        ->each(function (Order $order) {
            DB::transaction(function () use ($order) {

                if ($order?->user->accessCard && ! $order->is_exceptional && $order->user->accessCard->quota_lunch > 0) {
                    $order->user->accessCard->decrement('quota_lunch');

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
            });
        });


        return Command::SUCCESS;
    }
}