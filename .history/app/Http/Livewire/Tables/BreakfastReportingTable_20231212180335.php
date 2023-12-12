<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use App\States\Order\Completed;
use App\Support\DateTimeHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
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
                TextColumn::make('user.full_name')->label('NOM & PRÉNOM'),
                TextColumn::make('state')
                    ->label('Utilisateur')
                    ->formatStateUsing(fn (Order $row) => $row->user->full_name),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(date('d-m-Y') . '- breakfastReporting - export'),
                ]),
            ])
            ->filters([
                Filter::make('created_at')->form([
                    Select::make('period')->options(DateTimeHelper::getPeriod())->default('this_week')->label('Période'),
                ])->query(function (Builder $query, array $data) {
                    return $query->when(
                        $data['period'],
                        fn ($query, $period) => $query->whereBetween('created_at', DateTimeHelper::inThePeriod($period))
                    );
                })
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
