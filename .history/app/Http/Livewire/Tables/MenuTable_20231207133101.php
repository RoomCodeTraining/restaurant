<?php

namespace App\Http\Livewire\Tables;

use App\Models\Menu;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
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
                TextColumn::make('id')->label('MENU DU')->dateTime('d/m/Y')->searchable(),
                TextColumn::make('created_at')->label('Entrées')->formatStateUsing(fn (Menu $menu) => $menu->starter->name)
                    ->searchable(),

                TextColumn::make('updated_at')->label('PLAT 1')
                    ->formatStateUsing(fn (Menu $menu) => $menu->main_dish->name)
                    ->searchable(),

                TextColumn::make('deleted_at')->label('PLAT 2')->formatStateUsing(fn (Menu $menu) => $menu->second_dish?->name)
                    ->searchable(),

                TextColumn::make('id.updated')->label('DÉSSERT')->formatStateUsing(fn (Menu $menu) => $menu->dessert->name)
                    ->searchable(),

            ])->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('Editer')
                        ->url(fn (Department $record): string => route('departments.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (Department $record) {
                            //DepartmentDeleted::dispatch($record);
                            Notification::make()->title('Département supprimé supprimé avec succès !')->danger()->send();
                            return redirect()->route('departments.index');
                        })
                        ->hidden(fn (Department $record) => $record->users->count() > 0)
                        ->hidden(!Auth::user()->isAdmin())
                        ->action(fn (Department $record) => $record->delete()),

                ]),
            ])
            ->bulkActions([
                // ...
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
