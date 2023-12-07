<?php

namespace App\Http\Livewire\Tables;

use App\Models\Menu;
use Livewire\Component;
use Filament\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class MenuSpecialTable extends Component
{

    public function table(Table $table): Table
    {
        //dd(Menu::query()->with('dishes')->withCount('orders')->get());
        return $table

            ->query(Menu::query()->with('dishes')->withCount('orders')->latest())
            ->columns([
                TextColumn::make('served_at')->label('MENU DU')->dateTime('d/m/Y')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Entrées')->formatStateUsing(fn (Menu $menu) => $menu->starter->name),


                TextColumn::make('updated_at')->label('PLAT 1')
                    ->formatStateUsing(fn (Menu $menu) => $menu->main_dish->name),


                TextColumn::make('id')->label('PLAT 2')->formatStateUsing(fn (Menu $record) => $record->secondDish->name ? $record->secondDish->name : 'Aucun'),


                TextColumn::make('dishes')->label('DÉSSERT')->formatStateUsing(fn (Menu $record) => $record->dessert->name),


            ])->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([

                    Action::make('Consulter')
                        ->url(fn (Menu $record): string => route('menus.show', $record))
                        ->icon('heroicon-o-eye'),

                    Action::make('Editer')
                        ->url(fn (Menu $record): string => route('menus.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (Menu $record) {
                            //MenuDeleted::dispatch($record);
                            Notification::make()->title('Menu supprimé supprimé avec succès !')->danger()->send();
                            return redirect()->route('Menus.index');
                        })
                        // ->hidden(fn (Menu $record) => $record->users->count() > 0)
                        ->hidden(!Auth::user()->isAdminLunchRoom())
                        ->visible(fn (Menu $record) => $record->orders_count === 0)
                        ->action(fn (Menu $record) => $record->delete()),

                ]),
            ])
            ->bulkActions([
                // ...
            ]);
    }
    public function render()
    {
        return view('livewire.tables.menu-special-table');
    }
}
