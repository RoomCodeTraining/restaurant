<?php

namespace App\Http\Livewire\Tables;

use App\Exports\QuotaExport;
use App\Exports\UserExport;
use App\Http\Livewire\Tables\Actions\UserTableAction;
use App\Imports\UsersImport;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class UserTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";
    public string $tableName = 'users';

    public array $filterNames = [
        'type' => 'Profil',
        'active' => 'Etat du compte',
    ];

    public array $bulkActions = [
        'exportToUser' => 'Export en Excel',
        'exportQuota' => 'Export quotas en Excel',
    ];

    public $userIdBeingLocked;
    public $confirmingUserLocking = false;

    public $userIdBeingUnlocked;
    public $confirmingUserUnlocking = false;

    public $userIdBeingDeletion;
    public $confirmingUserDeletion = false;

    public $userIdBeingLunch;
    public $confirmingUserLunch = false;

    public $userIdBeingReset;
    public $confirmingUserReset = false;

    public $userIdAccessCardBeingReset;
    public $confirmingUserAccessCardReset = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\User::query()->latest())
            ->columns([
                TextColumn::make('identifier')
                    ->label('Matricule')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('NOM & PRENOMS')
                    ->searchable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('contact')->label('Contact'),
                TextColumn::make('role.name')->label('Profil'),
                TextColumn::make('created_at')
                    ->label('Etat du compte')
                    ->badge()
                    ->color(fn (User $row) => $row->is_active ? 'success' : 'danger')
                    ->formatStateUsing(function (User $row) {
                        return $row->is_active ? 'Actif' : 'Inactif';
                    }),

                TextColumn::make('id')
                    ->hidden()
                    ->label('Numéro de carte NFC')
                    ->formatStateUsing(fn (User $record) => $record->currentAccessCard->identifier ?? 'Aucune carte associée'),

                TextColumn::make('is_active')
                    ->formatStateUsing(fn (User $record) => $record->accessCard ? $record->accessCard->breakfast_reload_count : 'Aucun rechargement')
                    ->label('Réchargement petit dejeuner')
                    ->hidden(),

                TextColumn::make('updated_at')
                    ->formatStateUsing(fn (User $record) => $record->accessCard ? $record->accessCard->lunch_reload_count : 'Aucun rechargement')
                    ->label('Réchargement déjeuner')
                    ->hidden(),
            ])
            ->headerActions([
                Action::make('import_users')
                    ->color('success')
                    ->label(__('Importer les utilisateurs'))
                    ->icon('heroicon-m-user-group')
                    ->form([
                        FileUpload::make('file')
                            ->label(__('Choisir un fichier'))
                            ->rules('required', 'mimes:xlsx, xls, csv')
                            ->required(),
                    ])
                    ->modalHeading('Importer les utilisateurs')
                    ->action(function (array $data) {
                        $path = $data['file'];
                        (new UsersImport())->import($path);
                        Notification::make()
                            ->title('Importation des utilisateurs')
                            ->body('Les utilisateurs ont été importés avec succès.')
                            ->success();

                        return redirect()->route('users.index');
                    }),

                Action::make('create')
                    ->icon('heroicon-m-plus')
                    ->url(route('users.create'))
                    ->label(__('Ajouter un utilisateur'))
                //->modalMaxWidth('2xl'),
            ])
            ->filters([
                SelectFilter::make('user_type_id')
                    ->label('Profil')
                    ->relationship('role', 'name'),
                SelectFilter::make('is_active')
                    ->label('Etat du compte')
                    ->options([
                        '1' => 'Actif',
                        '0' => 'Inactif',
                    ]),
            ])
            ->actions((new UserTableAction())->getActions())
            ->bulkActions([
                BulkAction::make('export')
                    ->label('Exporter')
                    ->action(function (Collection $record) {
                        return Excel::download(new UserExport($record), now()->format('d-m-Y') . ' Liste-Utilisateurs.xlsx');
                    }),

                BulkAction::make('edit')
                    ->label('Exporter le quota')
                    ->action(function (Collection $records) {
                        return Excel::download(new QuotaExport($records), now()->format('d-m-Y') . ' Quota-Utilisateurs.xlsx');
                    }),
            ]);
    }

    //     public function export(Excel $excel, InvoicesExport $export)
    // {
    //     return $excel->download($export, 'invoices.xlsx');
    // }

    // public function exportToUser()
    // {
    //     return Excel::download(new UserExport(), 'utilisateurs.xlsx');
    // }

    public function render()
    {
        return view('livewire.tables.user-table');
    }
}