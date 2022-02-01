<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateBreakfastOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:generate-breakfast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les commandes de petit déjeuner pour les employés';

    /** 
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::query()->whereHas('accessCard')->each(function (User $user) {
            $user->orders()->create(['type' => 'breakfast']);
        });

        return Command::SUCCESS;
    }
}
