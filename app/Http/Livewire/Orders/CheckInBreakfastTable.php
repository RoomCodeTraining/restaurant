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

    public function columns(): array
    {
        return [
            Column::make('Menu du')->format(fn ($val, $col, $row) => $row->created_at->format('d/m/Y')),
            Column::make('Statut')->format(fn ($val, $col, Order $row) => view('livewire.orders.check-state', ['order' => $row])),
        ];
    }

    public function query(): Builder
    {
        return \App\Models\Order::query()->whereUserId(Auth::id())->withoutGlobalScope('lunch');
    }
}
