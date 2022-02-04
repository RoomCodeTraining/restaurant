<?php

namespace App\Console\Commands;

use App\Models\AccessCard;
use App\Models\Order;
use App\Models\User;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteTemporaryCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cards:delete-temporary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimes les cartes temporaires et transfere les coûts à la carte principale';

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
        AccessCard::query()->with('user')->whereType(AccessCard::TYPE_TEMPORARY)->whereDate('expires_at', '<=', now())->get()->each(function ($card) {
            DB::transaction(function () use ($card) {
                $user = User::firstWhere('current_access_card_id', $card->id);
                $primaryCard = $user->accessCards()->firstWhere('type', AccessCard::TYPE_PRIMARY);
                $user->update(['current_access_card_id' => $primaryCard->id]);

                $orders = Order::query()
                    ->withoutGlobalScopes()
                    //->whereNotNull('payment_method_id')
                    ->whereBelongsTo($card)
                    ->whereState('state', [Confirmed::class, Completed::class])
                    ->get()
                    ->groupBy('type');

                $breakfastOrdersCount = $orders->has('breakfast') ? $orders->get('breakfast')->count() : 0;
                $lunchOrdersCount = $orders->has('lunch') ? $orders->get('lunch')->count() : 0;

                $primaryCard->update([
                    'quota_breakfast' => max($primaryCard->quota_breakfast - $breakfastOrdersCount, 0),
                    'quota_lunch' => max($primaryCard->quota_lunch - $lunchOrdersCount, 0),
                ]);

                $card->delete();
            });
        });

        return Command::SUCCESS;
    }
}
