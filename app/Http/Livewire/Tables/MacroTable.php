<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\BillingHelper;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class MacroTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;


    public function getTotalOrder(User $record) : int
    {
        $ordersCount = $record->orders()->whereState('state', [Confirmed::class, Completed::class])->count();

        return $ordersCount;
    }


    public function table(Table $table) : Table
    {
        return $table
            ->query(User::with('accessCard', 'orders', 'userType')->withCount('orders'))
            ->columns([
                 TextColumn::make('accessCard.identifier')->label('N°Carte')->searchable(),
                 TextColumn::make('userType.name')->label('N°Carte')->searchable(),
                 TextColumn::make('full_name')->label('Nom & Prénoms')->searchable(),
                 TextColumn::make('id')
                 ->label('Total commande')->searchable()->formatStateUsing(function ($record) {
                     return $this->getTotalOrder($record);
                 }),
                 TextColumn::make('identifier')->label('Contribution collaborateur')->searchable()->formatStateUsing(fn ($record) => BillingHelper::getUserLunchContribution($record)['contribution'] * $this->getTotalOrder($record)),
                 TextColumn::make('email')->label('Subvention Ciprel')->searchable()->formatStateUsing(fn ($record) => BillingHelper::getUserLunchContribution($record)['subvention'] * $this->getTotalOrder($record))
        ]);
    }

    public function render()
    {
        return view('livewire.tables.macro-table');
    }
}