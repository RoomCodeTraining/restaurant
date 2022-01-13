<div>
    <x-no-access-card/>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        @if (auth()->user()->current_access_card_id)
            <x-statistic label="Commande du jour" value="{{ $today_order ? $today_order->dish->name : 'Aucune' }}"
                icon="plat" />
            <x-statistic label="quota dejeuner" value="{{ auth()->user()->currentAccessCard->quota_breakfast }}"
                icon="card" />
            <x-statistic label="quota petit dejeuner" value="{{ auth()->user()->currentAccessCard->quota_lunch }}"
                icon="card" />
        @endif
    </div>
</div>
