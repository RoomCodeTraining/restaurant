<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BreakfastCompletedTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Order::query()
                    ->whereState('state', Completed::class)
                    ->whereIn('type', ['lunch', 'breakfast'])
                    ->whereUserId(Auth::id())
                    ->withoutGlobalScope('lunch'),
            )
            ->columns([
                TextColumn::make('id')->label('Pointage du')->formatStateUsing(fn ($val, $col, $row) => $row->type == 'lunch' ? $row->menu->served_at->format('d/m/Y') : $row->created_at->format('d/m/Y')),
                TextColumn::make('type')->label('Type')->formatStateUsing(fn ($val, $col, Order $row) => $row->type == 'lunch' ? 'Déjeuner' : 'Petit déjeuner'),
            ])->emptyStateHeading('Aucun historique de pointage disponible pour le moment');
    }

    public function render()
    {
        return view('livewire.tables.breakfast-completed-table');
    }
}
