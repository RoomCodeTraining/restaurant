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

class BreakfastReportingTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(self::getTableQuery())
            ->columns([
                TextColumn::make('created_at')
                    ->formatStateUsing(fn (Order $row) => $row->created_at->format('d/m/Y'))
                    ->label('POINTAGE DU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.identifier')->label('MATRICULE/IDENTIFIANT'),
                TextColumn::make('user.full_name')->label('Nom & Prénoms')->searchable(),
            ])
            ->bulkActions([
                BulkAction::make('export')->label('Exporter')
                    ->action(function (Collection $record) {
                        return Excel::download(new OrdersExport($record), now()->format('d-m-Y') . ' RapportDesPointages.xlsx');
                    }),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Du'),
                        DatePicker::make('to')->label('Au')
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['from'] && $data['to'], function (Builder $query) use ($data) {
                            $query->whereBetween('created_at', [
                                $data['from'],
                                $data['to'],
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
            // ->whereState('state', Completed::class)
            ->withoutGlobalScope('lunch')
            ->whereIn('type', ['breakfast'])
            ->whereState('state', [Completed::class])
            ->with('user')
            ->latest();
    }

    public function render()
    {
        return view('livewire.tables.breakfast-reporting-table');
    }
}
