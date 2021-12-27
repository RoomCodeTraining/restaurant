<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\States\Order\Completed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChargeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debit:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réduction du quota de déjeuner';

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
        /**
         * Débiter le quota lunch des utilisateurs qui ont une commande pour le jour en cours.
         */
        Order::with('user.accessCard')->today()->each(function (Order $order) {
            if ($order->state->canTransitionTo(Completed::class)) {
                DB::transaction(function () use ($order) {
                    $order->user->accessCard->decrement('quota_lunch');
                });
            }
        });
    }
}
