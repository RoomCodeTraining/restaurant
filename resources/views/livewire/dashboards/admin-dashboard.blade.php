<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <x-statistic label="Utilisateurs" value="{{ $users_count }}" icon="users" />
        <x-statistic label="Utilisateurs actifs" value="{{ $active_users_count }}" icon="grid" />
        <x-statistic label="Utilisateurs inactifs" value="{{ $inactive_users_count }}" icon="grid" />
    </div>
</div>
