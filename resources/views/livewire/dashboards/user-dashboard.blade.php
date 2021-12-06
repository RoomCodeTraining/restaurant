<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <x-statistic label="Commande du jour" value="{{ $today_order ? $today_order->dish->name : 'Aucune' }}" icon="plat" />
        <x-statistic label="Consommations quotidienne" value="{{ $weekly_orders_count }}" icon="actifuser" />
        <x-statistic label="Consommations mensuelle" value="{{ $monthly_order_count }}" icon="inactifuser" />
    </div>
</div>
