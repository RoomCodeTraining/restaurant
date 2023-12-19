<?php

namespace App\Http\Livewire\Tables;

use App\Models\PaymentMethod;
use Carbon\Carbon;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use App\Models\ReloadAccessCardHistory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

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
                ->label('Date')
                ->form([
                    DatePicker::make('Du'),
                    DatePicker::make('Au'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['Du'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['Au'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['Du'] ?? null) {
                        $indicators[] = Indicator::make('Du ' . Carbon::parse($data['Du'])->format("d/m/Y"))
                            ->removeField('from');
                    }

                    if ($data['Au'] ?? null) {
                        $indicators[] = Indicator::make('Au ' . Carbon::parse($data['Au'])->format("d/m/Y"))
                            ->removeField('until');
                    }

                    return $indicators;
                }),

            SelectFilter::make('accessCard.paymentMethod.name')
                ->label('Profil')


                Filter::make('identifier')
                ->form([
                    Select::make('identifier')
                        ->placeholder('Sélectionner')
                        ->options(self::getFilterTable()),
                ])
                ->query(function ($query, array $data) {
                    if ($data['identifier'] == 'E') {
                        return $query
                            ->where('identifier', 'not like', '%' . 'I' . '%')
                            ->where('identifier', 'not like', '%' . 'C' . '%')
                            ->where('identifier', 'not like', '%' . 'P' . '%')
                            ->where('identifier', 'not like', '%' . 'S' . '%');
                    } elseif ($data['identifier'] == 'admin_security') {
                        return $query->whereHas('roles', function ($query) {
                            $query->where('name', 'admin_security');
                        });
                    } elseif ($data['identifier'] == 'admin') {
                        return $query->whereHas('roles', function ($query) {
                            $query->where('name', 'super_admin');
                        });
                    }
                    return $query->where('identifier', 'like', '%' . $data['identifier'] . '%');
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['identifier'] == 'C') {
                        $indicators['identifier'] = ' La catégorie sélectionnée est :  ' . 'Consultants';
                    }
                    if ($data['identifier'] == 'S') {
                        $indicators['identifier'] = 'La catégorie sélectionnée est ' . 'Stagiaires';
                    }
                    if ($data['identifier'] == 'I') {
                        $indicators['identifier'] = 'La catégorie sélectionnée est ' . 'Intérimaires';
                    }
                    if ($data['identifier'] == 'P') {
                        $indicators['identifier'] = ' La catégorie sélectionnée est ' . 'Prestataires';
                    }
                    if ($data['identifier'] == 'E') {
                        $indicators['identifier'] = ' La catégorie sélectionnée est ' . 'Employé Ciprel';
                    }
                    return $indicators;
                }),

        ]);
    }

    public function render()
    {
        return view('livewire.tables.top-up-table');
    }
}