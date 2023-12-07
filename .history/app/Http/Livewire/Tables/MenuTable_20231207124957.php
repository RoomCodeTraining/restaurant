<?php

namespace App\Http\Livewire\Tables;

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
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug'),

            ]);
    }
    public function render()
    {
        return view('livewire.tables.menu-table');
    }
}
