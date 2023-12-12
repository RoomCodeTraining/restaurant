<?php

namespace App\Http\Livewire\Tables;

use App\Models\Menu;
use App\Models\Order;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class WeeklyOrderTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;



    public function table(Table $table): Table
    {

        return $table
            ->query(self::getTableQuery())
            ->columns([
                TextColumn::make('menu_served_at')
                    ->label('Menu du')
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('dish_id')
                    ->label(__('Plat'))
                    ->formatStateUsing(fn (Order $row) => dishName($row->dish_id)),
                TextColumn::make('total_orders')->label(__('Nbr. de commandes')),
            ])
            ->actions([
                Action::make('show')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip(__('Consulter les utilisateurs'))
                    ->modalHeading(fn (Order $row) => 'Utilisateurs ayant commandé le plat ' . dishName($row['dish_id']) . ' le ' . Carbon::parse($row['menu_served_at'])->format('d/m/Y'))
                    ->modalContent(fn (Order $row) => view('orders.summary.modals', ['dish_id' => $row->dish_id, 'served_at' => $row->menu_served_at]))
                    ->modalWidth(MaxWidth::TwoExtraLarge)
                    ->modalSubmitAction(false)
            ]);
    }

    private static function getTableQuery()
    {
        $queryBuilder = Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotIn('state', [Cancelled::class, Suspended::class])
            ->groupBy('dish_id', 'menus.served_at')
            ->orderBy('menus.served_at', 'DESC')
            ->selectRaw('orders.*, menus.served_at as menu_served_at, COUNT(*) as total_orders');

        return $queryBuilder;
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
        $data = $menu
            ->orders()
            ->whereNotState('state', [Cancelled::class, Suspended::class])
            ->with('user')
            ->get();
        $this->users = $data->filter(fn ($order) => $order->dish_id == $row['dish_id'])->map(fn ($order) => $order->user);
        $this->showingUsers = true;
    }

    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}
