<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class UserMealOrdersTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table)
    {
        return $table->query(User::query())->columns([
            TextColumn::make('identifier')
                ->searchable()
                ->sortable(),
            TextColumn::make('full_name')
                ->searchable()
                ->sortable(),
        ]);
    }

    public function render()
    {
        return view('livewire.tables.user-meal-orders-table');
    }
}