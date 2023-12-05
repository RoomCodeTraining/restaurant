<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class UserTypeTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\UserType::query())
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM'),
                TextColumn::make('users_count')->label('NBR D\'ÉMPLOYÉS'),
                TextColumn::make('description')->label('Description'),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.user-type-table');
    }
}
