<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <a href='{{ route('today.orders.summary') }}'><x-statistic label="Commandes du jour" value="{{ $today_orders_count }}" icon="users" /></a>
        @if($menu)
            <a href='{{ route('today.orders.summary') }}'><x-statistic label="{{ $menu->main_dish->name }}" value="{{ $main_dish_count }}" icon="plat" /></a>
            <a href='{{ route('today.orders.summary') }}'><x-statistic label="{{ $menu->second_dish->name }}" value="{{ $second_dish_count }}" icon="plat" /></a>
        @endif
       <a href='#'><x-statistic label="Commande(s) consommée(s)" value="{{ $orders_completed_count }}" icon="plat" /></a>
        <a hre='#'><x-statistic label="Commande(s) non consommée(s)" value="{{ $today_orders_count - $orders_completed_count }}" icon="plat" /></a>
    </div>
</div>
