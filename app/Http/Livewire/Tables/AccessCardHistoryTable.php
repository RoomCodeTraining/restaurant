<?php

namespace App\Http\Livewire\Tables;

use App\Models\AccessCardHistory;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class AccessCardHistoryTable extends Component implements HasTable, HasForms
{
    use \Filament\Tables\Concerns\InteractsWithTable;
    use \Filament\Forms\Concerns\InteractsWithForms;

    public function table(Table $table) : Table
    {
        return $table->query(\App\Models\AccessCardHistory::query()->latest())
            ->columns([
                TextColumn::make('attached_at')->sortable()->label("Date & Heure d'opération")->dateTime('d/m/Y H:i:s'),
                TextColumn::make('accessCard.identifier')->sortable()->searchable()->label('Numéro de carte'),
                TextColumn::make('user.full_name')->sortable()->searchable()->label('Nom & Prénoms'),
                TextColumn::make('detached_at')
                ->sortable()
                ->searchable()->label('Date & Heure de détachement')->dateTime('d/m/Y H:i:s'),
                TextColumn::make('id')->label('Statut')->formatStateUsing(function (AccessCardHistory $row) {
                    return ! $row->detached_at ? 'Utilisé' : 'Déttachée';
                })
                ->badge()
                ->color(fn (AccessCardHistory $row) => $row->accessCard->is_used ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ]);
    }

    public function render()
    {
        return view('livewire.tables.access-card-history-table');
    }
}