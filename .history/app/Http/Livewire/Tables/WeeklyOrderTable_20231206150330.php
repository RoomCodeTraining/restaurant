<?php

namespace App\Http\Livewire\Tables;

use Carbon\Carbon;
use App\Models\Menu;
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

    public bool $showSearch = false;

    public $refresh = 1000 * 60;

    public $showingUsers = false;
    public $users = [];

    public function table(Table $table): Table
    {


        return $table
            ->query(
                Order::latest(),
            )
            ->columns([
                TextColumn::make('menu_served_at')
                    ->label('MENU DU')
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('dish.name')
                    ->label('PLAT'),
                TextColumn::make('total_orders')->label('NBRS DE COMMANDES'),
                TextColumn::make('id')->formatStateUsing(fn ($val, $col, $row) => view('orders.summary.table-actions', ['row' => $row]))
            ]);
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
        $data = $menu->orders()->whereNotState('state', [Cancelled::class, Suspended::class])->with('user')->get();
        $this->users = $data->filter(fn ($order) => $order->dish_id == $row['dish_id'])->map(fn ($order) => $order->user);
        $this->showingUsers = true;
    }

    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}
