<?php

namespace App\Http\Livewire\Tables;

use Carbon\Carbon;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\PaymentMethod;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use App\Models\ReloadAccessCardHistory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;


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
            })->with('accessCard.user', 'accessCard.paymentMethod')
                ->latest()
        )->columns([
            TextColumn::make('created_at')->label('Recharger le')->dateTime('d/m/Y H:i:s'),
            TextColumn::make('accessCard.identifier')
                ->label('N° de la carte')
                ->searchable()
                ->sortable(),
            TextColumn::make('accessCard.user.identifier')->label('Matricule')->hidden(),
            TextColumn::make('accessCard.user.full_name')
                ->label('Nom complet')
                ->searchable()
                ->sortable(),

            TextColumn::make('accessCard.user.employeeStatus.name')->label('Catégorie professionnelle')->hidden(),
            TextColumn::make('accessCard.user.department.name')->label('Fonction')->hidden(),
            TextColumn::make('accessCard.user.organization.name')->label('Sociéte')->hidden(),
            TextColumn::make('accessCard.user.role.name')->label('Type de collaborateur')->hidden(),
            TextColumn::make('accessCard.user.organization.name')->label('Sociéte')->hidden(),

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

        ])->headerActions([
            ExportAction::make()->exports([
                ExcelExport::make()
                    ->fromTable()
                    ->withFilename(date('d-m-Y') . '- Rechargements - export'),
            ]),
        ])
            ->filters([
                SelectFilter::make('quota_type')
                    ->label('Type')
                    ->options([
                        'breakfast' => 'Pétit déjeuner',
                        'lunch' => 'Déjeuner',
                    ]),
                // ->relationship('author', 'name'),

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


            ]);
    }

    public static function getFilterTable(): array
    {

        return [
            '' => 'Tous',
            'C' => 'Cash',
            'P' => 'Postpaid',
            'S' => 'Subvention',
        ];

        // return [
        //     '' => 'Tous',
        //     'admin_security' => 'Admin sureté',
        //     'admin' => 'Super Administrateur',
        // ];
    }

    public function render()
    {
        return view('livewire.tables.top-up-table');
    }
}
