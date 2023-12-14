<?php

namespace App\Http\Livewire\Tables;

use App\Models\AccessCard;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class AccessCardTable extends Component implements HasForms, HasTable
{
    use \Filament\Tables\Concerns\InteractsWithTable;
    use \Filament\Forms\Concerns\InteractsWithForms;

    public function table(Table $table) : Table
    {
        return $table->query(\App\Models\AccessCard::query()->latest())
            ->columns([
                TextColumn::make('identifier')->sortable()->searchable()->label('Numéro de carte'),
                TextColumn::make('user.full_name')->sortable()->searchable()->label('Nom & Prénoms'),
                TextColumn::make('id')->label('Statut')->formatStateUsing(function (AccessCard $row) {
                    return $row->is_used ? 'Utilisé' : 'Disponible';
                })
                ->badge()
                ->color(fn (AccessCard $row) => $row->is_used ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ]);
    }

    public function render()
    {
        return view('livewire.tables.access-card-table');
    }
}
