<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <x-statistic label="Commande total du jour" value="{{ $orders_confirmed_count + $orders_completed_count }}" icon="plat" />
        <x-statistic label="Commandes du jour consommées" value="{{ $orders_confirmed_count }}" icon="plat" />
        <x-statistic label="Commande du jour non consommées" value="{{ $orders_completed_count }}" icon="plat" />
        <x-statistic label="Utilisateur(s) actif(s)" value="{{ $active_users_count }}" icon="actifuser" />
        <x-statistic label="Utilisateur(s) inactif(s)" value="{{ $inactive_users_count }}" icon="inactifuser" />
        @if (auth()->user()->current_access_card_id)
            <x-statistic label="Cota dejeuner" value="{{ auth()->user()->currentAccessCard->quota_breakfast }}" icon="card" />
            <x-statistic label="Cota petit dejeuner" value="{{ auth()->user()->currentAccessCard->quota_lunch }}" icon="card" />
        @endif
    </div>
</div>
