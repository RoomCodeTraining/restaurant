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
                    ->whereDate('menus.served_at', now())
                    ->whereNotState('state', [Cancelled::class, Suspended::class])
                    ->groupBy('dish_id', 'menu_served_at')
                    ->orderBy('menu_served_at', 'DESC')
                    ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders'),
            )
            ->columns([
                TextColumn::make('menu_served_at')
                    ->label('MENU DU')
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('dish.name')
                    ->label('PLAT'),
                // ->formatStateUsing(fn ($row) => dishName($row)),
                TextColumn::make('total_orders')->label('NBRS DE COMMANDES'),
            ]);
    }




    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}
