<?php

namespace App\Http\Livewire\Tables;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
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
                    // ->sortable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('user.full_name')
                    ->label('Nom & Prénoms')
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
                BulkAction::make('export')->label('Exporter')
                    ->action(function (Collection $record) {

                        return Excel::download(new OrdersExport($record), now()->format('d-m-Y') . ' RapportDesCommandes.xlsx');
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
                        DatePicker::make('from')->label('Du')->default(now()->startOfWeek()),
                        DatePicker::make('to')->label('Au')->default(now()->endOfWeek()),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query->with('menu')->whereHas('menu', function (Builder $query) use ($data) {
                            $query->whereBetween('served_at', [
                                $data['from'] ?? null,
                                $data['to'] ?? null,
                            ]);
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