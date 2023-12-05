<?php

namespace App\Http\Livewire\Tables;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\SuggestionBox;
use App\Models\SuggestionType;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class SuggestionTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    private static function getTableQuery(): Builder
    {
        if (auth()->user()->hasRole(\App\Models\Role::ADMIN_TECHNICAL)) {
            return SuggestionBox::query()->with('suggestionType')->whereHas('suggestionType', function ($query) {
                $query->whereId(SuggestionType::IMPROVEMENT_APPLICATION)->orWhere('user_id', auth()->user()->id);
            });
        }

        if (auth()->user()->hasRole(\App\Models\Role::ADMIN) || auth()->user()->hasRole(\App\Models\Role::ADMIN_RH)) {
            return SuggestionBox::query()
                //  ->when($this->getFilter('created_at'), fn ($query, $created_at) => $query->whereDate('created_at', $created_at))
                //  ->when($this->getFilter('suggestion_type_id'), fn ($query, $suggestion_type_id) => $query->where('suggestion_type_id', $suggestion_type_id))
                ->with('suggestionType')->latest();
        }

        return SuggestionBox::query()->with('suggestionType')->whereUserId(auth()->user()->id)->latest();
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->query(self::getTableQuery())
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('user.full_name')->label('SUGGERANT'),
                TextColumn::make('suggestionType.name')->label('OBJET'),
                TextColumn::make('suggestion')->label('SUGGESTIONS'),
            ])
            ->filters([


                SelectFilter::make('author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                // Filter::make('created_at')
                //     ->form([
                //         DatePicker::make('from'),
                //         DatePicker::make('until'),
                //     ])
                //     // ...
                //     ->indicateUsing(function (array $data): array {
                //         $indicators = [];

                //         if ($data['from'] ?? null) {
                //             $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['from'])->toFormattedDateString())
                //                 ->removeField('from');
                //         }

                //         if ($data['until'] ?? null) {
                //             $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['until'])->toFormattedDateString())
                //                 ->removeField('until');
                //         }

                //         return $indicators;
                //     })
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(date('d-m-Y') . '- Suggestions - export'),
                ]),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.suggestion-table');
    }
}
