<?php

namespace App\Http\Livewire\Tables;

use App\Exports\CheckInBreakfastExport;
use App\Exports\MenuExport;
use App\Models\Menu;
use Livewire\Component;
use Filament\Tables\Table;
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

class MenuTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;


    public function table(Table $table): Table
    {
        //dd(Menu::query()->with('dishes')->withCount('orders')->get());
        return $table

            ->query(Menu::query()->with('dishes')->withCount('orders')->latest())
            ->columns([

                TextColumn::make('served_at')->label('MENU DU')->dateTime('d/m/Y')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Entrées')->formatStateUsing(fn (Menu $menu) => $menu->starter ? $menu->starter->name : 'Aucun'),
                TextColumn::make('updated_at')->label('PLAT 1')
                    ->formatStateUsing(fn (Menu $menu) => $menu->main_dish->name),
                TextColumn::make('id')->label('PLAT 2')->formatStateUsing(fn (Menu $record) => $record->secondDish ? $record->secondDish->name : 'Aucun'),
                TextColumn::make('dishes')->label('DÉSSERT')->formatStateUsing(fn (Menu $record) => $record->dessert ? $record->dessert->name : 'Aucun'),


            ])->filters([
                // ...
            ])
            ->actions([
                Action::make('Consulter')
                    ->label('')
                    ->color('success')
                    ->url(fn (Menu $record): string => route('menus.show', $record))
                    ->icon('heroicon-o-eye')->tooltip('Consulter'),

                Action::make('Editer')
                    ->label('')
                    ->color('secondary')
                    ->tooltip('Modifier')
                    ->url(fn (Menu $record): string => route('menus.edit', $record))
                    ->hidden(fn (Menu $record) => !$record->canBeUpdated() || !Auth::user()->isAdminLunchRoom())
                    ->icon('heroicon-o-pencil-square'),

                Action::make('Supprimer')
                    ->label('')
                    ->tooltip('Supprimer')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->before(function (Menu $record) {
                        //MenuDeleted::dispatch($record);
                        Notification::make()->title('Menu supprimé supprimé avec succès !')->danger()->send();

                        return redirect()->route('Menus.index');
                    })
                    ->hidden(fn (Menu $record) => !$record->canBeUpdated() || !Auth::user()->isAdminLunchRoom())
                    ->visible(fn (Menu $record) => $record->orders_count === 0)
                    ->action(fn (Menu $record) => $record->delete()),
            ])
            ->bulkActions([
                BulkAction::make('export')->label('Exporter')
                    ->action(function (Collection $record) {
                        return Excel::download(new MenuExport(), now()->format('d-m-Y') . ' Menu.xlsx');
                    }),
            ]);
    }

    public function modalsView(): string
    {
        return 'livewire.menus.modals';
    }

    public function render()
    {
        return view('livewire.tables.menu-table');
    }
}
