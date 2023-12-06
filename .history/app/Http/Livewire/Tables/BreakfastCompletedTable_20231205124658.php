<?php

namespace App\Http\Livewire\Tables;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

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
                TextColumn::make('id')->label('Pointage du')->formatStateUsing(fn ($val, $col, $row) => $row->type == 'lunch' ? $row->menu->served_at->format('d/m/Y') : $row->created_at->format('d/m/Y')),
                TextColumn::make('type')->label('Type')->formatStateUsing(fn ($val, $col, Order $row) => $row->type == 'lunch' ? 'Déjeuner' : 'Petit déjeuner'),
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

            ])

            ->emptyStateHeading('Aucun historique de pointage disponible pour le moment');
    }

    public function render()
    {
        return view('livewire.tables.breakfast-completed-table');
    }
}
