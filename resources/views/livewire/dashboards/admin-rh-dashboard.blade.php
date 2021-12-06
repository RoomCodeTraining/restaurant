<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <x-statistic label="Carte(s) attribueé(s)" value="{{ $active_access_card_count }}" icon="card" />
        <x-statistic label="Utilisateur(s) actif(s)" value="{{ $active_users_count }}" icon="actifuser" />
        <x-statistic label="Utilisateur(s) inactif(s)" value="{{ $inactive_users_count }}" icon="inactifuser" />
    </div>
</div>
