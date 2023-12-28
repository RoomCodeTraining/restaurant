<?php

namespace App\Http\Livewire\Tables;

use App\Exports\ReloadAccessCardHistoryExport;
use App\Models\PaymentMethod;
use App\Models\ReloadAccessCardHistory;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class TopUpTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;


    public function table(Table $table): Table
    {
        return $table->query(
            ReloadAccessCardHistory::query()
                ->whereHas('accessCard', function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('deleted_at', null);
                    });
                })->with('accessCard.user', 'accessCard.paymentMethod', 'accessCard')
                ->latest()
        )->columns([
            TextColumn::make('created_at')->label('Recharger le')->dateTime('d/m/Y H:i:s'),
            TextColumn::make('accessCard.identifier')
                ->label('N° de la carte')
                ->searchable()
                ->sortable(),
            TextColumn::make('accessCard.user.full_name')
                ->label('Nom complet')
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

        ])
            ->bulkActions([
                BulkAction::make('export')->label('Exporter')
                    ->action(function (Collection $record) {
                        return Excel::download(new ReloadAccessCardHistoryExport($record), now()->format('d-m-Y') . 'Historique-Des-Recharges.xlsx');
                    }),
            ])
            ->filters([
                SelectFilter::make('quota_type')
                    ->label('Type')
                    ->options([
                        'breakfast' => 'Petit déjeuner',
                        'lunch' => 'Déjeuner',
                    ]),
                Filter::make('payment_method')->label('Moyen de paiement')
                    ->form([
                    Select::make('payment_method_id')
                        ->label('Moyen de paiement')
                        ->options(PaymentMethod::pluck('name', 'id')->toArray())
                ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['payment_method_id'],
                            fn (Builder $query, $payment_method_id): Builder => $query->with('accessCard')->whereHas('accessCard', function ($query) use ($payment_method_id) {
                                $query->where('payment_method_id', $payment_method_id);
                            }),
                        );
                    }),
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

    public function render()
    {
        return view('livewire.tables.top-up-table');
    }
}