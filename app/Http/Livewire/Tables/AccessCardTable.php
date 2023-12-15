<?php

namespace App\Http\Livewire\Tables;

use App\Models\AccessCard;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;

class AccessCardTable extends Component implements HasForms, HasTable
{
    use \Filament\Tables\Concerns\InteractsWithTable;
    use \Filament\Forms\Concerns\InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table->query(\App\Models\AccessCard::query()->latest())
            ->columns([
                TextColumn::make('created_at')->label('Date')->DateTime('d/m/Y'),
                TextColumn::make('identifier')->searchable()->label('Numéro de carte'),
                TextColumn::make('quota_breakfast')->sortable()->searchable()->label('Quota petit déjeuner'),
                TextColumn::make('quota_lunch')->sortable()->searchable()->label('Quota déjeuner'),
                TextColumn::make('user.full_name')->searchable()->label('Nom & Prénoms'),
                TextColumn::make('type')->sortable()->searchable()->label('Type de carte')->formatStateUsing(function (AccessCard $row) {
                    return $row->is_used ? 'Primaire' : 'Temporaire';
                }),
                TextColumn::make('id')->label('Statut')->formatStateUsing(function (AccessCard $row) {
                    return $row->is_used ? 'Utilisé' : 'Disponible';
                })
                    ->badge()
                    ->color(fn (AccessCard $row) => $row->is_used ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type de la carte')
                    ->options([
                        'primary' => 'Primaire',
                        'temporary' => 'Temporaire',
                    ]),

                SelectFilter::make('is_used')
                    ->label('Statut')
                    ->options([
                        '1' => 'La carte est utilisée',
                        '0' => 'La carte est disponible',

                    ])
            ])->actions([
                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip(__("Consulter l'historique de la carte"))
                    ->modalHeading('Voir l\'utilisateur')
                    ->modalContent(fn (AccessCard $accessCard) => view('access-cards.histories', compact('accessCard')))
                    ->modalWidth('2xl')
            ]);
    }

    public function render()
    {
        return view('livewire.tables.access-card-table');
    }
}