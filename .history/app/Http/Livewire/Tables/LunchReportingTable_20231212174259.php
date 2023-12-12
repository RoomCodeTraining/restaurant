<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use App\Support\DateTimeHelper;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class LunchReportingTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table($table)
    {
        return $table
            ->query(self::getTableQuery())
            ->columns([
                TextColumn::make('menu.served_at')
                    ->label('Menu du')
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('user.full_name')
                    ->label('NOM 1 PRÉNOM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dish.name')
                    ->label('Plat')
                    ->searchable(),
                TextColumn::make('state')
                    ->label('Statut')
                    ->formatStateUsing(fn (Order $row) => $row->state->title())
                    ->badge()
                    ->color(fn (Order $row) => $row->state == 'confirmed' ? 'secondary' : 'success'),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(date('d-m-Y') . '- LunchReporting - export'),
                ]),
            ])
            ->filters([
                Filter::make('served_at')
                    ->form([
                        Select::make('period')
                            ->options(DateTimeHelper::getPeriod())
                            ->default('this_week')
                            ->label('Période'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query->with('menu')->whereHas('menu', function (Builder $query) use ($data) {
                            $query->whereBetween('served_at', DateTimeHelper::inThePeriod($data['period']));
                        });

                        return $query;
                    }),
            ])
            ->emptyStateHeading('Aucun déjeuner trouvé')
            ->emptyStateIcon('heroicon-o-moon');
    }

    private static function getTableQuery()
    {
        $queryBuilder = Order::with('dish')
            // ->join('orders.*', 'menus.served_at as menu_served_at')
            ->join('menus', 'menus.id', '=', 'orders.menu_id')
            ->select('orders.*', 'menus.served_at as menu_served_at')
            ->whereNotState('state', [Cancelled::class, Suspended::class])
            ->latest();

        return $queryBuilder;
    }

    public function render()
    {
        return view('livewire.tables.weekly-reporting-table');
    }
}
