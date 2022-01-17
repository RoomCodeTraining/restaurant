<?php

namespace App\Http\Livewire\Orders;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TodayOrdersExport;
use App\States\Order\Cancelled;
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

    public $dish_name;


    public array $bulkActions = [
        'exportOrders' => 'Export au format Excel',
    ];

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
        $orders =  Order::today()->join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereNotState('state', Cancelled::class)
            ->groupBy('dish_id', 'menu_served_at')
            ->orderBy('menu_served_at', 'DESC')
            ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders');
            return $orders;
    }


    public function modalsView(): string
    {
        return 'orders.summary.modals';
    }

    public function showUsers($row)
    {
        $this->dish_name = \App\Models\Dish::find($row['dish_id'])->name;

        $date = Carbon::parse($row['menu_served_at']);
        $menu = Menu::query()
            ->whereDate('served_at', $date)
            ->first();
       
        $this->users = $menu->orders()
            ->with('user')
            ->whereNotState('state', Cancelled::class)
            ->get()
            ->filter(fn ($order) => $order->dish_id == $row['dish_id'])
            ->map(fn ($order) => $order->user);


        $this->showingUsers = true;
    }



    public function exportOrders(){
        $file_name = "Les commandes du ".Carbon::today()->locale('FR_fr')->isoFormat('dddd D MMMM YYYY');
        return Excel::download(new TodayOrdersExport(), $file_name.'.xlsx');
    }

    
}
