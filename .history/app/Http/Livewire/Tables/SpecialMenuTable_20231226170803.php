<?php

namespace App\Http\Livewire\Tables;

use App\Exports\DishExport;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\MenuSpecial;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class SpecialMenuTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {

        return $table
            ->query(MenuSpecial::query()->latest())
            ->columns([
                TextColumn::make('served_at')->label('Menu du')->dateTime('d/m/Y')->searchable()->sortable(),
                TextColumn::make('dish.name')->label('Plat'),
            ])->filters([
                // ...
            ])
            ->actions([
                Action::make('Editer')
                    ->label('')
                    ->color('secondary')
                    ->url(fn (MenuSpecial $record): string => route('menus-specials.edit', $record))
                    ->hidden(!Auth::user()->can('manage', \App\Models\Menu::class))
                    ->icon('heroicon-o-pencil-square')
                    ->tooltip('Modifier'),



                Action::make('Supprimer')
                    ->label('')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Supprimer')
                    ->before(function (MenuSpecial $record) {
                        //MenuDeleted::dispatch($record);
                        Notification::make()->title('Menu B supprimé avec succès !')->danger()->send();

                        return redirect()->route('menus-specials');
                    })
                    ->hidden(Auth::user()->can('manage', \App\Models\Menu::class))
                    ->visible(fn (MenuSpecial $record) => $record->orders_count === 0)
                    ->action(fn (MenuSpecial $record) => $record->delete()),
            ])
            ->bulkActions([
                BulkAction::make('export')->label('Exporter')
                    ->action(function (Collection $record) {
                        return Excel::download(new DishExport($record), now()->format('d-m-Y') . ' Liste-des-Menu-A.xlsx');
                    }),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.special-menu-table');
    }
}
