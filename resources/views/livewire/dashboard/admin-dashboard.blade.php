<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
        <a href='{{ route('orders.index') }}'>
            <x-statistic label="Commandes totales du jour" value="75" icon="plat" />
        </a>
        <a href='#'>
            <x-statistic label="Commande(s) consommée(s)" value="10" icon="plat" />
        </a>
        <a hre='#'>
            <x-statistic label="Commande(s) non consommée(s)" value="10" icon="plat" />
        </a>
    </div>
</div>
