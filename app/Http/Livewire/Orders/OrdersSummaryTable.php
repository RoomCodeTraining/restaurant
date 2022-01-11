<?php

namespace App\Http\Livewire\Orders;

use App\Models\Menu;
use App\Models\Order;
use App\States\Order\Confirmed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OrdersSummaryTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public bool $showSearch = false;

    public $refresh = 1000 * 60;

    public $showingUsers = false;
    public $users = [];

    public function columns(): array
    {
        return [
            Column::make('Menu du', 'menu_served_at'),
            Column::make('Plat', 'dish_name'),
            Column::make('Nbr. de commandes', 'total_orders'),
            Column::make('Actions')->format(fn ($val, $col, $row) => view('orders.summary.table-actions', ['row' => $row]))
        ];
    }

    public function query()
    {
        $orders =  Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereState('state', Confirmed::class)
            ->groupBy('dish_id', 'served_at')
            ->orderBy('served_at', 'DESC')->get();

            dd($orders);
           /*->selectRaw('
                DATE_FORMAT(menus.served_at, "%d/%m/%Y") as menu_served_at,
                dishes.id AS dish_id,
                dishes.name AS dish_name,
                COUNT(orders.id) AS total_orders
            ');*/
    }

    public function modalsView(): string
    {
        return 'orders.summary.modals';
    }

    public function showUsers($row)
    {
        $menu = Menu::query()
            ->whereDate('served_at', Carbon::createFromFormat('d/m/Y', $row['menu_served_at']))
            ->first();

        $this->users = $menu->orders()
            ->with('user')
            ->whereState('state', Confirmed::class)
            ->get()
            ->filter(fn ($order) => $order->dish_id == $row['dish_id'])
            ->map(fn ($order) => $order->user);

        $this->showingUsers = true;
    }
}
