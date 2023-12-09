<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class LogTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;


    public function table(Table $table) : Table
    {
        return $table
            ->query(Activity::query()->latest())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y'),
                TextColumn::make('id')->label('Heure')->formatStateUsing(fn (Activity $row) => $row->created_at->format('H:i:s')),
                TextColumn::make('causer.identifier')
                    ->label('Matricule')
                    ->sortable(),
                TextColumn::make('causer.full_name')
                    ->label('Nom complet')
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Action')
                    ->sortable(),
            ]);
    }


    public function render()
    {
        return view('livewire.tables.log-table');
    }
}