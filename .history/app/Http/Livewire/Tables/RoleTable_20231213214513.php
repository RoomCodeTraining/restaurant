<?php

namespace App\Http\Livewire\Tables;

use App\Models\Role;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class RoleTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table->query(
            \App\Models\Role::query()->withCount('users')
        )->columns([
            TextColumn::make('created_at')->label('Date de création')->dateTime('d/m/Y'),
            TextColumn::make('name')->label('Nom'),
            TextColumn::make('description')->label('Description'),
            TextColumn::make('users_count')->label('Nombre d\'utilisateurs'),
        ])->actions([
            ViewAction::make('edit')
                ->label('')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->tooltip(__('Modifier les permissions'))
                ->url(fn (Role $row) => route('roles.edit', $row->id)),
            DeleteAction::make('delete')
                ->label('')
                ->icon('heroicon-o-trash')
                ->tooltip(__('Supprimer le rôle'))
                ->modalHeading(__('Supprimer le rôle'))
                ->modalDescription(__('Êtes-vous sûr de vouloir supprimer ce rôle ?'))
                ->action(function (Role $role) {
                    $role->delete();
                })
        ]);
    }

    public function render()
    {
        return view('livewire.tables.role-table');
    }
}
