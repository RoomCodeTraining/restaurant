<?php

namespace App\Http\Livewire\Orders;

use App\Models\Menu;
use App\Models\Order;
use App\States\Order\Confirmed;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
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
            Column::make('Menu du', 'menu_served_at')->format(fn ($row) => Carbon::parse($row)->format('d/m/Y')),
            Column::make('Plat', 'dish_id')->format(fn ($row) => dishName($row)),
            Column::make('Nbr. de commandes', 'total_orders'),
            Column::make('Actions')->format(fn ($val, $col, $row) => view('orders.summary.table-actions', ['row' => $row]))
        ];
    }

    public function query(): Builder
    {
        return   Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotState('state', Cancelled::class)
            ->groupBy('dish_id', 'menu_served_at')
            ->orderBy('menu_served_at', 'DESC')
            ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders');
    }

    public function modalsView(): string
    {
        return 'orders.summary.modals';
    }

    public function showUsers($row)
    {

        $date = Carbon::parse($row['menu_served_at']);
        $menu = Menu::query()
            ->whereDate('served_at', $date)
            ->first();


           
        $data = $menu->orders()->whereNotState('state', Cancelled::class)->with('user')->get();
        $this->users = $data->filter(fn($order) => $order->dish_id == $row['dish_id'])->map(fn ($order) => $order->user);
        $this->showingUsers = true;
    }
}
