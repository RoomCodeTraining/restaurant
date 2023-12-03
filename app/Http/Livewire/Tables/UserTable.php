<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class UserTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\User::query())
            ->columns([
                TextColumn::make('created_at')->label('Date de création')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('full_name')->label('Nom'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('contact'),
                TextColumn::make('current_card')->label('N° carte'),
                TextColumn::make('role.name')->label('Rôle'),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.user-table');
    }
}