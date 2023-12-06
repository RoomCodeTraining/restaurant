<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class MenuTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table) : Table
    {
        return $table
            ->query(\App\Models\Menu::orderBy('served_at', 'desc'))
            ->columns([
                TextColumn::make('served_at')->label('MENU DU')->dateTime('d/m/Y')->sortable(),
                TextColumn::make('starter.name')->label('ENTRÉE'),
                TextColumn::make('main_dish.name')->label('PLAT PRINCIPAL'),
                TextColumn::make('second_dish.name')->label('PLAT SECONDAIRE'),
                TextColumn::make('dessert.name')->label('DESSERT'),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.menu-table');
    }
}