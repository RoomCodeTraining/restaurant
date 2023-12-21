<?php

namespace App\Http\Livewire\Tables;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\States\Order\Completed;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

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
                        return Excel::download(new OrdersExport($record), now()->format('d-m-Y') . ' RapportDesPointages.xlsx');
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
                        DatePicker::make('from')->label('Du')->default(now()->startOfWeek()),
                        DatePicker::make('to')->label('Au')->default(now()->endOfWeek()),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->with('menu')->whereHas('menu', function (Builder $query) use ($data) {
                            $query->whereBetween('served_at', [
                                $data['from'] ?? null,
                                $data['to'] ?? null,
                            ]);
                        });
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