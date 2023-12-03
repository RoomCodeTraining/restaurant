<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
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
                TextColumn::make('full_name')->label('Nom complet'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('contact'),
                TextColumn::make('department.name')->label('Département'),
                TextColumn::make('organization.name')->label('Organisation'),
                TextColumn::make('current_access_card_id')->label('N° carte')->formatStateUsing(function (User $row) {
                    return $row->current_access_card_id ? $row->accessCard->identifier : 'Aucune carte';
                }),
                TextColumn::make('role.name')->label('Rôle'),
            ])->actions([

                    EditAction::make()->label('')->icon('heroicon-o-pencil')->color('info')->tooltip('Modifier')->url(function (User $user) {
                        return route('users.edit', $user);
                    }),
                    DeleteAction::make()->label('')->icon('heroicon-o-trash')->tooltip('Desactiver')->hidden(function (User $user) {
                        return $user->id == auth()->user()->id && $user->isActive();
                    })->requiresConfirmation(),
                    Action::make('reactivate')->label('')->icon('heroicon-o-lock-open')->tooltip('Activer')->hidden(function (User $user) {
                        return $user->isActive();
                    }),

            ]);
    }

    public function render()
    {
        return view('livewire.tables.user-table');
    }
}