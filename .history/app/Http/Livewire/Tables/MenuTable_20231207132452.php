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

        return $table
        dd(Menu::query()->with('dishes')->withCount('orders')->get());
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

            ]);
    }
    public function render()
    {
        return view('livewire.tables.menu-table');
    }
}