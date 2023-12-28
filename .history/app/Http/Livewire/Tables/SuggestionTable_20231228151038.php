<?php

namespace App\Http\Livewire\Tables;

use Carbon\Carbon;
use App\Models\Role;
use Livewire\Component;
use App\Models\SuggestionBox;
use App\Models\SuggestionType;
use App\Support\ActivityHelper;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
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

        SuggestionBox::query()
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
                TextColumn::make('user.full_name')->label(__('Suggérant')),
                TextColumn::make('suggestionType.name')->label(__('Objet')),
                TextColumn::make('suggestion')
                    ->wrap()
                    ->label(__('Suggestions')),
            ])
            ->filters([
                SelectFilter::make('suggestionType')
                    ->label('Objet')
                    ->relationship('suggestionType', 'name')
                    ->hidden(Auth::user()->hasRole(\App\Models\Role::ADMIN_TECHNICAL)),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('Du'),
                        DatePicker::make('Au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Du'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['Au'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['Du'] ?? null) {
                            $indicators[] = Indicator::make('Du ' . Carbon::parse($data['Du'])->toFormattedDateString())
                                ->removeField('Du');
                        }

                        if ($data['Au'] ?? null) {
                            $indicators[] = Indicator::make('Au ' . Carbon::parse($data['Au'])->toFormattedDateString())
                                ->removeField('Au');
                        }

                        return $indicators;
                    })
            ])
            ->actions([

                EditAction::make()
                    // ->hidden(
                    //     fn () => !auth()
                    //         ->user()
                    //         ->hasRole(Role::ADMIN) && !auth()->user()->hasRole(Role::ADMIN_TECHNICAL),
                    // )
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->color('secondary')
                    ->tooltip('Modifier')
                    ->url(function (SuggestionBox $suggestion) {
                        return route('suggestions-box.edit', $suggestion);
                    })->hidden(fn ($record) => dd($record->user_id)),
                // Action::make('show')
                //     ->label('')
                //     ->icon('eye')
                //     ->tooltip(__('Consulter la suggestion'))
                //     ->modalHeading(fn (SuggestionBox $row) => 'Suggestion du ' . Carbon::parse($row['created_at'])->format('d/m/Y'))
                //     ->modalContent(fn (SuggestionBox $row) => view('suggestions.edit', ['suggestion' => $row]))
                //     ->modalWidth(MaxWidth::TwoExtraLarge)
                //     ->modalSubmitAction(false),

                Action::make('delete')
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Supprimer la suggestion')
                    ->requiresConfirmation()
                    ->hidden()
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


  // Filter::make('created_at')->form([
                //     DatePicker::make('created_from')
                //         ->label('Date'),
                // ])
                //     ->query(
                //         function (Builder $query, array $data) {
                //             if ($data['created_from'] == null) {
                //                 return $query;
                //             }
                //             return $query
                //                 ->when(
                //                     $data['created_from'],
                //                     function (Builder $query, $date) {
                //                         $suggestion = SuggestionBox::query()->whereDate('created_at', \Carbon\Carbon::parse($date))->first();
                //                         return $query->whereId($suggestion?->id);
                //                     },
                //                 );
                //         }
                //     )
                //     ->indicateUsing(function (array $data): array {
                //         $indicators = [];
                //         if ($data['created_from'] ?? null) {
                //             $indicators['from'] = 'date :  ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                //         }
                //         return $indicators;
                //     })