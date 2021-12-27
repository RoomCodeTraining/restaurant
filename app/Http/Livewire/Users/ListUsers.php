<?php

namespace App\Http\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Closure;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Actions\IconButtonAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListUsers extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return User::query()->with('role');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('identifier')->label('Matricule/Identifiant')->searchable(),
            TextColumn::make('full_name')->label('Nom & Prénoms')->searchable(['first_name', 'last_name']),
            TextColumn::make('email')->label('Email')->searchable(),
            TextColumn::make('contact')->label('Contact')->searchable(),
            TextColumn::make('role.name')->label('Profil'),
            BooleanColumn::make('is_active')->label('Etat du compte'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            IconButtonAction::make('show')
                ->label("Voir l'utilisateur")
                ->url(fn (User $record): string => route('users.show', $record))
                ->icon('heroicon-o-eye'),
            IconButtonAction::make('edit')
                ->label("Modifier l'utilisateur")
                ->url(fn (User $record): string => route('users.edit', $record))
                ->color('success')
                ->icon('heroicon-o-pencil'),
            IconButtonAction::make('delete')
                ->label("Supprimer l'utilisateur")
                ->action(fn (User $record) => $record->delete())
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->hidden(fn (User $record) => auth()->user()->id === $record->id)
                ->requiresConfirmation()
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('role')->label('Rôle')->options(Role::pluck('name', 'id')->toArray())->relationship('role', 'id'),
        ];
    }

    protected function getTableHeading(): string|Closure|null
    {
        return 'Liste des utilisateurs';
    }

    protected function getTableHeaderActions(): array
    {
        return [
            ButtonAction::make('add')
                ->label("Ajouter un utilisateur")
                ->url(route('users.create'))
                ->color('primary')
                ->icon('heroicon-o-plus')
                ->hidden(auth()->user()->cannot('manage', User::class)),
        ];
    }

    public function render()
    {
        return view('livewire.users.list-users');
    }
}
