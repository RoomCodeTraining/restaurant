<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use App\Exports\OrdersExport;
use App\States\Order\Completed;
use App\Support\DateTimeHelper;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class BreakfastReportingTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(self::getTableQuery())
            ->columns([
                // TextColumn::make('id')
                //     ->formatStateUsing(fn (Order $row) => $row->created_at->format('d/m/Y'))
                //     ->label('POINTAGE DU')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('pointing_at')
                    ->formatStateUsing(fn (Order $row) => $row->created_at->format('d/m/Y'))
                    ->label('POINTAGE DU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.identifier')->label('MATRICULE/IDENTIFIANT'),
                TextColumn::make('user.full_name')->label('NOM & PRÉNOM')->searchable(),
                // TextColumn::make('state')
                //     ->label('Utilisateur')
                //     ->formatStateUsing(fn (Order $row) => $row->user->full_name),
            ])
            ->bulkActions([
                BulkAction::make('export')->label('Exporter')
                    ->action(function (Collection $record) {
                        return Excel::download(new OrdersExport($record), now()->format('d-m-Y') . ' repoortingPointages.xlsx');
                    }),
            ])
            // ->headerActions([
            //     ExportAction::make()->exports([
            //         ExcelExport::make()
            //             ->fromTable()
            //             ->withFilename(date('d-m-Y') . '- breakfastReporting - export'),
            //     ]),
            // ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Select::make('period')
                            ->options(DateTimeHelper::getPeriod())
                            ->default('this_week')
                            ->label('Période'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['period'], fn ($query, $period) => $query->whereBetween('created_at', DateTimeHelper::inThePeriod($period)));
                    }),
            ])
            ->emptyStateHeading('Aucun petit déjeuner trouvé')
            ->emptyStateIcon('heroicon-o-sun');
    }

    private static function getTableQuery()
    {
        return \App\Models\Order::query()
            ->whereState('state', Completed::class)
            ->whereIn('type', ['breakfast'])
            ->with('user')
            ->withoutGlobalScope('lunch')
            ->latest();
    }

    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}
