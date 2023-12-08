<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class DayliReportingTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {

        return $table->query(self::getTableQuery())
            ->columns([
            TextColumn::make('menu_served_at')
                ->label('Menu du')
                ->searchable()
                ->sortable()
                ->dateTime('d/m/Y'),
            TextColumn::make('dish_id')
                ->label('Plat')
                ->formatStateUsing(fn (Order $row) => dishName($row->dish_id)),
            TextColumn::make('total_orders')->label('Nbr. de commandes'),
        ]);
    }

    private static function getTableQuery()
    {
        $queryBuilder = Order::join('dishes', 'orders.dish_id', 'dishes.id')
           ->join('menus', 'orders.menu_id', 'menus.id')
           ->whereDate('menus.served_at', now())
           ->whereNotIn('state', [Cancelled::class, Suspended::class])
           ->groupBy('dish_id', 'menus.served_at')
           ->orderBy('menus.served_at', 'DESC')
           ->selectRaw('orders.*, menus.served_at as menu_served_at, COUNT(*) as total_orders');

        return $queryBuilder;
    }
    public function render()
    {
        return view('livewire.tables.dayli-reporting-table');
    }
}