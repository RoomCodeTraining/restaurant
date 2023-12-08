<?php

namespace App\Http\Livewire\Tables;

use App\Models\SuggestionBox;
use App\Models\SuggestionType;
use App\Support\ActivityHelper;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SuggestionTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    private static function getTableQuery(): Builder
    {
        if (
            auth()
                ->user()
                ->hasRole(\App\Models\Role::ADMIN_TECHNICAL)
        ) {
            return SuggestionBox::query()
                ->with('suggestionType')
                ->whereHas('suggestionType', function ($query) {
                    $query->whereId(SuggestionType::IMPROVEMENT_APPLICATION)->orWhere('user_id', auth()->user()->id);
                });
        }

        if (
            auth()
                ->user()
                ->hasRole(\App\Models\Role::ADMIN) ||
            auth()
                ->user()
                ->hasRole(\App\Models\Role::ADMIN_RH)
        ) {
            return SuggestionBox::query()
                ->with('suggestionType')
                ->latest();
        }

        return SuggestionBox::query()
            ->with('suggestionType')
            ->whereUserId(auth()->user()->id)
            ->latest();
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->query(self::getTableQuery())
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Date de création'))
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('user.full_name')->label(__('Suggerant')),
                TextColumn::make('suggestionType.name')->label(__('Objet')),
                TextColumn::make('suggestion')->label(__('Suggestions')),
            ])
            ->filters([
                SelectFilter::make('suggestionType')
                    ->label('Objet')
                    ->relationship('suggestionType', 'name'),

                Filter::make('created_at')
                    ->label('Date')
                    ->form([DatePicker::make('date')->default(now())])
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['date']) {
                            return null;
                        }

                        return 'Suggestion du ' . Carbon::parse($data['date'])->toFormattedDateString();
                    }),
            ])
            ->actions([
                // Action::make('show')
                //     ->label('')
                //     ->icon('eye')
                //     ->tooltip(__('Consulter la suggestion'))
                //     ->modalHeading(fn (SuggestionBox $row) => 'Suggestion du ' . Carbon::parse($row['created_at'])->format('d/m/Y'))
                //     ->modalContent(fn (SuggestionBox $row) => view('suggestions.show', ['suggestion' => $row]))
                //     ->modalWidth(MaxWidth::TwoExtraLarge)
                //     ->modalSubmitAction(false),
                Action::make('delete')
                    ->label('')
                    ->icon('trash')
                    ->requiresConfirmation()
                    ->modalHeading(__('Supprimer la suggestion'))
                    ->modalDescription(__('Êtes-vous sûr de vouloir supprimer cette suggestion ?'))
                    ->action(function (SuggestionBox $suggestion) {
                        $suggestion->delete();

                        ActivityHelper::createActivity($suggestion, 'Suppression de la suggestion du ' . Carbon::parse($suggestion->created_at)->format('d-m-Y'), 'Suppression de la suggestion');

                        Notification::make()
                            ->title('Suggestion supprimée')
                            ->success()
                            ->body('La suggestion a été supprimée avec succès !')
                            ->icon('heroicon-o-trash')
                            ->send($suggestion->user);
                    }),
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