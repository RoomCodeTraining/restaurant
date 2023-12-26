<div>
    @dd($dish_of_day)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <a href='{{ route('today.orders.summary') }}'><x-statistic label="Commandes totales"
                value="{{ $today_orders_count }}" icon="users" /></a>
        <a hre='#'><x-statistic label="Commande  jour" value="{{ $sun_orders_count }}" icon="cde" /></a>
        <a hre='#'><x-statistic label="Commande soir" value="{{ $journey_orders_count }}" icon="cde" /></a>
        @if ($menu)
            <a href='{{ route('today.orders.summary') }}'><x-statistic label="{{ $menu->main_dish->name }}"
                    value="{{ $main_dish_count }}" icon="plat" /></a>
            @if ($menu->second_dish)
                <a href='{{ route('today.orders.summary') }}'><x-statistic label="{{ $menu->second_dish->name }}"
                        value="{{ $second_dish_count }}" icon="plat" /></a>
            @endif
        @endif
        <a href='#'><x-statistic label="Commande(s) consommée(s)" value="{{ $orders_completed_count }}"
                icon="plat" /></a>
        <a hre='#'><x-statistic label="Commande(s) non consommée(s)"
                value="{{ $today_orders_count - $orders_completed_count }}" icon="plat" /></a>

        <a hre='#'><x-statistic label="{{ $dish_of_day }}" value="" icon="plat" /></a>
    </div>
</div>
