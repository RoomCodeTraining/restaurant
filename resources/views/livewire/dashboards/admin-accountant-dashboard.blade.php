<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <x-statistic label="Consommations mensuelle des cadres"
            value="{{ $guest_monthly_orders_count ? $guest_monthly_orders_count->count() : 0 }}" icon="plat" />
        <x-statistic label="Consommations mensuelle des agents"
            value="{{ $ciprel_agent_monthly_orders_count ? $ciprel_agent_monthly_orders_count->count() : 0 }}"
            icon="plat" />
        <x-statistic label="Consommations mensuelle des stagiaires"
            value="{{ $intern_monthly_orders_count ? $intern_monthly_orders_count->count() : 0 }}" icon="plat" />
        <x-statistic label="Consommations mensuelle des agents non ciprel"
            value="{{ $others_monthly_orders_count ? $others_monthly_orders_count->count() : 0 }}" icon="plat" />
        <x-statistic label="COmmandes mensuelle consommées" value="{{ $monthly_orders_completed }}"
            icon="inactifuser" />
        <x-statistic label="COmmandes mensuelle annulées" value="{{ $monthly_orders_cancelled }}"
            icon="inactifuser" />
        <x-statistic label="COmmandes mensuelle suspendus" value="{{ $monthly_orders_suspended }}"
            icon="inactifuser" />
        @if (auth()->user()->current_access_card_id)
            <x-statistic label="Cota dejeuner" value="{{ auth()->user()->currentAccessCard->quota_breakfast }}"
                icon="card" />
            <x-statistic label="Cota petit dejeuner" value="{{ auth()->user()->currentAccessCard->quota_lunch }}"
                icon="card" />
        @endif
    </div>
</div>
