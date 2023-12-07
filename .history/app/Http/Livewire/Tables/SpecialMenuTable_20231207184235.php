<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use App\Models\MenuSpecal;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class SpecialMenuTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        dd("mum");
        return $table
            ->query(MenuSpecal::query()->latest())
            ->columns([
                TextColumn::make('served_at')->label('MENU DU')->dateTime('d/m/Y')->searchable()->sortable(),
                TextColumn::make('dish.name')->label('Plat'),
            ])->filters([
                // ...
            ]);
        // ->actions([
        //     ActionGroup::make([

        //         Action::make('Editer')
        //             ->url(fn (MenuSpecal $record): string => route('menus-specials.edit', $record))
        //             ->icon('heroicon-o-pencil'),

        //         Action::make('Supprimer')
        //             ->requiresConfirmation()
        //             ->icon('heroicon-o-trash')
        //             ->color('danger')
        //             ->before(function (Menu $record) {
        //                 //MenuDeleted::dispatch($record);
        //                 Notification::make()->title('Menu supprimé supprimé avec succès !')->danger()->send();
        //                 return redirect()->route('Menus.index');
        //             })

        //             ->hidden(Auth::user()->can('manage', \App\Models\Menu::class))
        //             //->visible(fn (MenuSpecal $record) => $record->orders_count === 0)
        //             ->action(fn (MenuSpecal $record) => $record->delete()),

        //     ]),
        // ])
        // ->bulkActions([
        //     // ...
        // ]);
    }
    public function render()
    {
        return view('livewire.tables.special-menu-table');
    }
}
