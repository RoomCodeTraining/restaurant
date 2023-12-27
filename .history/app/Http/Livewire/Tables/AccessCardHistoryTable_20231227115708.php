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

    public $accessCard;

    public function table(Table $table): Table
    {
        return $table->query(AccessCardHistory::query()->where('access_card_id', $this->accessCard->id)->latest())
            ->columns([
                TextColumn::make('attached_at')->sortable()->label("Attribuée le")->dateTime('d/m/Y'),
                TextColumn::make('id')
                    ->searchable()->label('Détacher le')
                    ->formatStateUsing(function (AccessCardHistory $row) {
                        return !is_null($row->detached_at) ? $row->detached_at->format('d/m/Y') : 'Non détachée';
                    }),
                TextColumn::make('user.full_name')->searchable()->label('Nom & Prénoms'),
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
