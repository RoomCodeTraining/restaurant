<?php

namespace App\Http\Livewire\Tables;

use App\Models\Organization;
use App\Support\ActivityHelper;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class OrganizationTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Organization::query()->withCount('users')->latest())
            ->columns([
                TextColumn::make('created_at')->label('Date de création')->searchable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('family')->label('Famille')
                    ->searchable()
                    ->badge()
                    ->color(fn (Organization $record) => $record->family == Organization::GROUP_1 ? 'primary' : 'gray'),
                TextColumn::make('users_count')->label('Nbr employés')->searchable(),
                TextColumn::make('is_entitled_two_dishes')
                ->label('2 plats')->searchable()
                ->tooltip(fn ($record) => $record->is_entitled_two_dishes ? 'Les employés de cette sociéte ont-ils droit à 2 plats' : 'Les employés de cette sociéte ont-ils droit à 1 plat')
                ->badge()->color(fn (Organization $record) => $record->is_entitled_two_dishes ? 'success' : 'gray')
                ->formatStateUsing(fn (Organization $record) => $record->is_entitled_two_dishes ? 'Oui' : 'Non'),
            ])->actions([
                Action::make('Editer')
                    ->url(fn (Organization $record): string => route('organizations.edit', $record))
                    ->color('secondary')
                    ->label('')
                    ->tooltip('Editer la sociéte')
                    ->icon('heroicon-o-pencil-square'),

                Action::make('delete')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('')
                    ->tooltip('Supprimer la sociéte')
                    ->requiresConfirmation()
                    ->modalHeading('Supprimer la sociéte')
                    ->modalDescription('Etes-vous sûr de vouloir supprimer cette sociéte ?')
                    ->hidden(fn (Organization $record) => $record->users->count() > 0)
                    ->action(function (Organization $record) {
                        $record->delete();
                        ActivityHelper::createActivity($record, 'Suppression du departement ' . $record->name, 'Suppression du departement');
                        Notification::make()->title('Suppression du departement')->success()->body('Le departement a été supprimé avec succès !')->send($record->user);
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.organization-table');
    }
}