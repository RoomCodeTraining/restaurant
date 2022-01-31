<div>
    <x-no-access-card />
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        @if (auth()->user()->current_access_card_id)
            <a href='{{ route('orders.index') }}'>
                @if ($today_order && $today_order->state == 'completed')
                    <x-statistic label="Commande du jour" value="{{ 'Deja consommée' }}" icon="plat" />
                @else
                    <x-statistic label="Commande du jour"
                        value="{{ $today_order ? $today_order->dish->name : 'Aucune' }}" icon="plat" />
                @endif
            </a>
            <a href='/profile#card'>
                <x-statistic label="quota petit dejeuner"
                    value="{{ auth()->user()->currentAccessCard->quota_breakfast }}" icon="card" />
            </a>
            <a href='/profile#card'>
                <x-statistic label="quota dejeuner" value="{{ auth()->user()->currentAccessCard->quota_lunch }}"
                    icon="card" />
            </a>
        @endif
        <a href='{{ route('orders.index') }}'>
            <x-statistic label="Commandes totales du jour" value="{{ $count_today_orders }}" icon="plat" />
        </a>
        <a href='#'>
            <x-statistic label="Commande(s) consommée(s)" value="{{ $orders_completed_count }}" icon="plat" />
        </a>
        <a hre='#'>
            <x-statistic label="Commande(s) non consommée(s)" value="{{ $orders_confirmed_count }}" icon="plat" />
        </a>
    </div>
</div>
