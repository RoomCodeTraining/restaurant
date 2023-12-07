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
    use InteractsWithForms, InteractsWithTable;


    public function table(Table $table): Table
    {
        dd(Menu::query()->with('dishes')->withCount('orders')->latest());
        return $table
            ->query(Menu::query()->with('dishes')->withCount('orders')->latest())
            ->columns([
                TextColumn::make('served_at')->label('MENU DU')->dateTime('d/m/Y')->searchable(),
                TextColumn::make('id')->label('Entrées')->formatStateUsing(fn (Menu $menu) => $menu->starter->name)
                    ->searchable(),

                TextColumn::make('')->label('PLAT 1'),
                TextColumn::make('')->label('PLAT 2'),
                TextColumn::make('')->label('DÉSSERT'),

            ]);
    }
    public function render()
    {
        return view('livewire.tables.menu-table');
    }
}
