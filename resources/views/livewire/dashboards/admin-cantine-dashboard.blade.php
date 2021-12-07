<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <x-statistic label="Commandes du jour" value="{{ $today_orders_count }}" icon="users" />
        <x-statistic label="{{ $menu->main_dish->name }}" value="{{ $main_dish_count }}" icon="plat" />
        <x-statistic label="{{ $menu->second_dish->name }}" value="{{ $second_dish_count }}" icon="plat" />
        <x-statistic label="Commande(s) retirée(s)" value="{{ $orders_completed_count }}" icon="plat" />
        <x-statistic label="Commande(s) en attente(s)" value="{{ $today_orders_count - $orders_completed_count }}" icon="plat" />
        <x-statistic label="Commande(s) annulée(s)" value="{{ $orders_cancelled_count }}" icon="plat" />
    </div>
</div>
