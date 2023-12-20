<?php

namespace App\Http\Livewire\Tables;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;
use App\Exports\UserExport;
use App\Exports\OrdersExport;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use App\Support\DateTimeHelper;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

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
                    // ->sortable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('user.full_name')
                    ->label('NOM & PRÉNOM')
                    ->searchable(),
                //->sortable(),
                TextColumn::make('dish.name')
                    ->label('Plat')
                    ->searchable(),

                TextColumn::make('state')
                    ->badge()
                    ->label('Statut')
                    ->formatStateUsing(function ($record) {
                        return $record->state->title();
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'secondary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })

            ])
            ->headerActions([

                // ExportAction::make()->exports([
                //     ExcelExport::make()
                //         ->fromTable()
                //         ->withFilename(date('d-m-Y') . '- LunchReporting - export'),
                // ]),

            ])->bulkActions([
                BulkAction::make('export')->label('Exporters')
                    ->action(function (Order $record) {

                        //dd($record->menu->menu_served_at); //menu_served_at
                        return Excel::download(new OrdersExport($record), now()->format('d-m-Y') . ' CommandesLunch.xlsx');
                    }),
            ])
            ->filters([

                SelectFilter::make('state')
                    ->label('Statut')
                    ->options([
                        'confirmed' => 'Commande confirmée',
                        'completed' => 'Commande consommée',
                        'cancelled' => 'Commande annulée',
                    ]),


                // Filter::make('created_at')
                //     ->form([
                //         DatePicker::make('Du'),
                //         DatePicker::make('Au'),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {

                //         return $query
                //             ->when(
                //                 $data['Du'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                //             )
                //             ->when(
                //                 $data['Au'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                //             );
                //     })
                //     ->indicateUsing(function (array $data): array {
                //         $indicators = [];

                //         if ($data['Du'] ?? null) {
                //             $indicators[] = Indicator::make(' Du  ' . Carbon::parse($data['Du'])->format('d/m/Y'))
                //                 ->removeField('Du');
                //         }

                //         if ($data['Au'] ?? null) {
                //             $indicators[] = Indicator::make('Jusqu\'au ' . Carbon::parse($data['Au'])->format('d/m/Y'))
                //                 ->removeField('Au');
                //         }

                //         return $indicators;
                //     }),
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
