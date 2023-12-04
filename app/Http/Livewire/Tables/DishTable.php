<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class DishTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table) : Table
    {
        return $table
            ->query(\App\Models\Dish::query())
             ->paginated([10, 25, 50, 100, 'all'])
            ->columns([
                TextColumn::make('created_at')->label('Date de création')->searchable()->sortable()->dateTime('d/m/Y'),
                ImageColumn::make('image')->label('Image'),
                TextColumn::make('name')->label('Nom'),
                // TextColumn::make('description')->label('Description'),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.dish-table');
    }
}