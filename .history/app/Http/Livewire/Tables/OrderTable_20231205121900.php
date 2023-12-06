<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class OrderTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Order::query())
            ->columns([
                TextColumn::make('created_at')->label('EFFECTUÉ LE')->dateTime('d/m/Y H:i:s'),
                TextColumn::make('menu.served_at')->label('MENU DU ')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('dish.name')->label('PLAT'),
                TextColumn::make('Statut')->label('state'),
                TextColumn::make('contact'),
                TextColumn::make('current_card')->label('N° carte'),
                TextColumn::make('role.name')->label('Rôle'),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.order-table');
    }
}
