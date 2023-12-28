<?php

namespace App\Http\Livewire\Tables;

use App\Exports\MenuExport;
use App\Exports\MenusExport;
use App\Models\Menu;
use App\Services\CustomDataExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class MenuTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;


    public function table(Table $table): Table
    {
        //dd(Menu::query()->with('dishes')->withCount('orders')->get());
        return $table

            ->query(Menu::query()->with('dishes')->withCount('orders')->latest())
            ->columns([

                TextColumn::make('served_at')->label('MENU DU')->dateTime('d/m/Y')
                    ->searchable()->sortable(),
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
                    ->hidden(fn (Menu $record) => ! $record->canBeUpdated() || ! Auth::user()->isAdminLunchRoom())
                    ->icon('heroicon-o-pencil-square'),

                Action::make('Supprimer')
                    ->label('')
                    ->tooltip('Supprimer')
                    ->modalHeading('Supprimer le menu')
                    ->modalDescription('Etes-vous sûr de vouloir supprimer ce menu ?')
                    // ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->before(function (Menu $record) {
                        //MenuDeleted::dispatch($record);
                        Notification::make()->title('Menu supprimé supprimé avec succès !')->danger()->send();

                        return redirect()->route('menus.index');
                    })
                    ->hidden(fn (Menu $record) => ! $record->canBeUpdated() || ! Auth::user()->isAdminLunchRoom())
                    ->visible(fn (Menu $record) => $record->orders_count === 0)
                    ->action(fn (Menu $record) => $record->delete()),
            ])
            ->bulkActions([
                BulkAction::make('export')->label('Exporter')
                    ->action(function (Collection $record) {
                        return Excel::download(new MenusExport($record), now()->format('d-m-Y') . ' Liste-des-Menu-A.xlsx');
                    }),
            ]);
        // ->bulkActions([
        //     // BulkAction::make('export')->label('Exporter')
        //     //     ->action(function (Collection $record) {
        //     //         //$data = (new CustomDataExport($record))->transform();
        //     //         // dd($data);
        //     //         return Excel::download(new  MenuTable($record), 'report.xlsx');
        //     //         // return Excel::download(new MenuExport($record), now()->format('d-m-Y') . ' Menu.xlsx');
        //     //     }),
        // ]);
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