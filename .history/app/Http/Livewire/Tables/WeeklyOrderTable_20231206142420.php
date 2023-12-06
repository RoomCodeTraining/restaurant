<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class WeeklyOrderTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::join('dishes', 'orders.dish_id', 'dishes.id')
                    ->join('menus', 'orders.menu_id', 'menus.id')
                    ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->whereNotState('state', [Cancelled::class, Suspended::class])
                    ->groupBy('dish_id', 'menu_served_at')
                    ->orderBy('menu_served_at', 'DESC')
                    ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders'),
            )
            ->columns([
                TextColumn::make('menu_served_at')->label('MENU DU')->dateTime('d/m/Y'),
                TextColumn::make('dish.name')->label('Plat'),
                TextColumn::make('total_orders')->label('NBR DE COMMANDES'),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }


    // public function modalsView(): string
    // {
    //     return 'orders.summary.modals';
    // }

    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}


// $weekly = Order::join('dishes', 'orders.dish_id', 'dishes.id')
//             ->join('menus', 'orders.menu_id', 'menus.id')
//             ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
//             ->whereNotState('state', [Cancelled::class, Suspended::class])
//             ->groupBy('dish_id', 'menu_served_at')
//             ->orderBy('menu_served_at', 'DESC')
//             ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders');

//         return $table
//             ->query($weekly)
//             ->paginated([10, 25, 50, 100, 'all'])
//             ->columns([

//                 //TextColumn::make('Actions')->format(fn ($val, $col, $row) => view('orders.summary.table-actions', ['row' => $row]))
//                 TextColumn::make('menu_served_at')->label('MENU DU')->dateTime('d/m/Y'),
//                 TextColumn::make('dish.name')->label('Plat'),
//                 TextColumn::make('total_orders')->label('NBR DE COMMANDES'),


//             ]);
