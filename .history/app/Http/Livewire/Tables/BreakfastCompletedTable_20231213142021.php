<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use App\States\Order\Completed;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class BreakfastCompletedTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Order::query()
                    ->whereState('state', Completed::class)
                    ->whereIn('type', ['lunch', 'breakfast'])
                    ->whereUserId(Auth::id())
                    ->withoutGlobalScope('lunch'),
            )
            ->columns([
                TextColumn::make('id')->label('Pointage du')->formatStateUsing(fn (Order $row) => $row->type == 'lunch' ? $row->menu->served_at->format('d/m/Y') : $row->created_at->format('d/m/Y')),
                TextColumn::make('pointed_at')->label('Pointé le')->formatStateUsing(fn (Order $row) => $row->pointed_at->format('d/m/Y H:i:s')),
                TextColumn::make('type')->label('Type')->formatStateUsing(fn (Order $row) => $row->type == 'lunch' ? 'Déjeuner' : 'Petit déjeuner'),
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
                            $indicators[] = Indicator::make('Du ' . Carbon::parse($data['Du'])->toFormattedDateString())
                                ->removeField('Du');
                        }

                        if ($data['Au'] ?? null) {
                            $indicators[] = Indicator::make('Jusqu\'au ' . Carbon::parse($data['Au'])->toFormattedDateString())
                                ->removeField('Au');
                        }

                        return $indicators;
                    })

            ])->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(date('d-m-Y') . '- HistoriquesPointages - export'),
                ]),
            ])
            ->emptyStateHeading('Aucun historique de pointage disponible pour le moment');
    }

    public function render()
    {
        return view('livewire.tables.breakfast-completed-table');
    }
}
