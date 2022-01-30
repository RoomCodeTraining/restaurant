<?php

namespace App\Http\Livewire\Orders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class CheckInBreakfastTable extends DataTableComponent
{

    public bool $showSearch = false;

    public function columns(): array
    {
        return [
            Column::make('Pointage du')->format(fn ($val, $col, $row) => $row->type == 'lunch' ? $row->menu->served_at->format('d/m/Y') : $row->created_at->format('d/m/Y')),
            Column::make('Type')->format(fn($val, $col, Order $row) => $row->type == 'lunch' ? 'Déjeuner' : 'Petit déjeuner'),
            Column::make('Statut')->format(fn ($val, $col, Order $row) => view('livewire.orders.check-state', ['order' => $row])),
        ];
    }

    public function query(): Builder
    {
        return \App\Models\Order::query()->whereIn('type', ['lunch', 'breakfast'])->whereUserId(Auth::id())->withoutGlobalScope('lunch');
    }
}
