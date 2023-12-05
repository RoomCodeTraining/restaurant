<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class DayliOrderTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

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
                TextColumn::make('total_orders')->label('NBR DE COMMANDES'),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.dayli-order-table');
    }
}
