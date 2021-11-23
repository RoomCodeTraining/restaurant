<?php

namespace App\Http\Livewire\Orders;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OrdersSummaryTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public bool $showSearch = false;

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

    public function query(): Builder
    {
        return Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
            // ->when($this->getFilter('search'), function ($query, $term) {
            //     $query->whereHas('dish', fn ($query) => $query->search($term));
            // })
            ->groupBy('dish_id', 'menu_served_at')
            ->orderBy('menu_served_at', 'DESC')
            ->selectRaw('
                DATE_FORMAT(menus.served_at, "%d/%m/%Y") as menu_served_at,
                dishes.id AS dish_id,
                dishes.name AS dish_name,
                COUNT(orders.id) AS total_orders
            ');
    }

    public function modalsView(): string
    {
        return 'orders.summary.modals';
    }

    public function showUsers($row)
    {
        $this->users = [];

        /**
         * @var $menu \App\Models\Menu
         */
        $menu = Menu::with('orders.user')->firstWhere('served_at', Carbon::createFromFormat('d/m/Y', $row['menu_served_at'])->format('Y-m-d'));
        $this->users = $menu->orders->filter(fn ($order) => $order->dish_id == $row['dish_id'])->map(fn ($order) => $order->user);
        $this->showingUsers = true;
    }
}
