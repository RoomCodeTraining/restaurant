<?php

namespace App\Http\Livewire\Tables;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
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
            ->query(
                Order::join('dishes', 'orders.dish_id', 'dishes.id')
                    ->join('menus', 'orders.menu_id', 'menus.id')
                    ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->whereNotState('state', [Cancelled::class, Suspended::class])
                    ->groupBy('dish_id', 'menu_served_at')
                    ->orderBy('menu_served_at', 'DESC')
                    ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders')
            )
            ->columns([
                TextColumn::make('menu_served_at')->label('Menu du')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('dish_id')->label('Plat')->formatStateUsing(fn ($row) => dishName($row)),
                TextColumn::make('total_orders')->label('Nbr. de commandes'),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(date('d-m-Y') . '- LunchReporting - export'),
                ]),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('Du'),
                        DatePicker::make('Au'),
                    ])
                    // ...
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['Du'] ?? null) {
                            $indicators[] = Indicator::make(' Du  ' . Carbon::parse($data['Du'])->format('d/m/Y'))
                                ->removeField('Du');
                        }

                        if ($data['Au'] ?? null) {
                            $indicators[] = Indicator::make('Jusqu\'au ' . Carbon::parse($data['Au'])->format('d/m/Y'))
                                ->removeField('Au');
                        }

                        return $indicators;
                    })
            ])->emptyStateHeading('Aucun déjeuner trouvé');
    }


    public function render()
    {
        return view('livewire.tables.weekly-reporting-table');
    }
}
