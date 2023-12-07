<?php

namespace App\Http\Livewire\Tables;

use App\Models\Dish;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class DishTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Dish::query()->withCount('orders'))
            ->paginated([10, 25, 50, 100, 'all'])
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('DATE CREATION'))
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y'),
                ImageColumn::make('image')
                    ->label(__('IMAGE'))
                    ->width(50)
                    ->height(50),
                TextColumn::make('name')
                    ->label(__('PLAT'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('orders_count')
                    ->label(__('COMMANDES ASSOCIEES'))
                    ->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->icon('pencil')
                    ->label(''),
                Action::make('delete')
                    ->icon('trash')
                    ->iconSize('6')
                    ->label('')
                    ->color('danger')
                    ->hidden(fn (Dish $record) => $record->orders_count > 0)
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-trash')
                    ->modalHeading(__('Suppression du plat'))
                    ->modalDescription(__('Êtes-vous sûr de vouloir supprimer ce plat ?'))
                    ->modalSubmitActionLabel(__('Supprimer'))
                    ->action(fn (Dish $record) => $record->forceDelete()),
                // ->successMessage(__('Le plat a bien été supprimé'))
                // ])
            ])
            ->bulkActions([]);
    }

    public function render()
    {
        return view('livewire.tables.dish-table');
    }
}