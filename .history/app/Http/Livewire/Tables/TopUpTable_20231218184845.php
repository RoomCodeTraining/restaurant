<?php

namespace App\Http\Livewire\Tables;

use App\Models\ReloadAccessCardHistory;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;

class TopUpTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;


    public function table(Table $table): Table
    {
        return $table->query(
            \App\Models\ReloadAccessCardHistory::query()->whereHas('accessCard', function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('deleted_at', null);
                });
            })->with('accessCard.user')
                ->latest()
        )->columns([
            TextColumn::make('created_at')->label('Recharger le')->dateTime('d/m/Y H:i:s'),
            TextColumn::make('accessCard.user.full_name')
                ->label('Nom complet')
                ->searchable()
                ->sortable(),
            TextColumn::make('accessCard.user.identifier')
                ->label('N° de la carte')
                ->searchable()
                ->sortable(),
            TextColumn::make('accessCard.paymentMethod.name')
                ->label('Moyen de paiement')
                ->searchable()
                ->sortable(),

            TextColumn::make('quota_type')
                ->label('Quota')
                ->formatStateUsing(fn (ReloadAccessCardHistory $row) => $row->quota_type == 'breakfast' ? 'Petit déjeuner' : 'Déjeuner')
                ->badge()
                ->color(fn (ReloadAccessCardHistory $row) => $row->quota_type == 'breakfast' ? 'primary' : 'success')
                ->icon(fn (ReloadAccessCardHistory $row) => $row->quota_type == 'breakfast' ? 'heroicon-o-sun' : 'heroicon-o-moon')
                ->searchable()
                ->sortable(),
            TextColumn::make('quota')
                ->label('Nombre de quota')
                ->searchable()
                ->sortable(),
        ])->filters([

            Filter::make('created_at')
                ->form([
                    DatePicker::make('from'),
                    DatePicker::make('until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['from'] ?? null) {
                        $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['from'])->toFormattedDateString())
                            ->removeField('from');
                    }

                    if ($data['until'] ?? null) {
                        $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['until'])->toFormattedDateString())
                            ->removeField('until');
                    }

                    return $indicators;
                })
            // SelectFilter::make('payment_method_id')
            //     ->label('Mode de paiement')
            //     ->relationship('payment_method', 'name'),

            // SelectFilter::make('accessCard.paymentMethod')
            //     ->relationship('accessCard.paymentMethod', 'name')

            // SelectFilter::make('accessCard.paymentMethod.name'),

            // SelectFilter::make('is_active')
            //     ->label('Etat du compte')
            //     ->options([
            //         '1' => 'Actif',
            //         '0' => 'Inactif',
            //     ]),
        ]);
    }

    public function render()
    {
        return view('livewire.tables.top-up-table');
    }
}
