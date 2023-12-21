<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use Filament\Tables\Table;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class UserMealOrdersTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public $dish_id;
    public $served_at;

    public function mount($dish_id, $served_at)
    {
        $this->dish_id = $dish_id;
        $this->served_at = $served_at;
    }

    public function table(Table $table)
    {
        return $table->query(
            \App\Models\Order::where('dish_id', $this->dish_id)
                ->with('user')
                ->whereNotIn('state', [Cancelled::class, Suspended::class])
                ->whereHas('menu', function ($query) {
                    $query->where('served_at', $this->served_at);
                })->latest()
        )->columns([
            TextColumn::make('user.identifier')
                ->label('Matricule')
                ->searchable()
                ->sortable(),
            TextColumn::make('user.full_name')
                ->label('Nom complet')
                ->searchable()
                ->sortable(),

            TextColumn::make('dish.name')
                ->label('Plat')
                ->searchable()
                ->sortable(),
        ])->headerActions([
            ExportAction::make()->exports([
                ExcelExport::make()
                    ->fromTable()
                    ->withFilename('CommandeJournalière du' . date('d-m-Y')),
            ]),
        ]);
    }

    public function render()
    {
        return view('livewire.tables.user-meal-orders-table');
    }
}
