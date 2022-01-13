<?php

namespace App\Http\Livewire\Orders;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TodayOrders extends DataTableComponent
{

    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public bool $showSearch = false;

    public $refresh = 1000 * 60;

    public $showingUsers = false;
    public $users = [];

    public function columns(): array
    {
        return [
            Column::make('Menu du', 'menu_served_at')->format(fn($row) => Carbon::parse($row)->format('d/m/Y')),
            Column::make('Plat', 'dish_id')->format(fn($row) => dishName($row)),
            Column::make('Nbr. de commandes', 'total_orders'),
            Column::make('Actions')->format(fn ($val, $col, $row) => view('orders.summary.table-actions', ['row' => $row]))
        ];
    }

    public function query(): Builder
    {
        return Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->today()
            ->whereState('state', Confirmed::class)
            ->groupBy('dish_id', 'menu_served_at')
            ->orderBy('menu_served_at', 'DESC')
            ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders');
    }
}
