<?php

namespace App\Http\Livewire\Tables;

use App\Exports\UserExport;
use App\Http\Livewire\Tables\Actions\UserTableAction;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

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
            // ->paginated([10, 25, 50, 100, 'all'])
            ->columns([
                TextColumn::make('identifier')
                    ->label('Matricule')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')->label('NOM & PRENOMS')->searchable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('role.name')->label('Profil'),
                TextColumn::make('is_active')
                    ->label('Etat')
                    ->badge()
                    ->color(fn (User $row) => $row->isActive() ? 'success' : 'danger')
                    ->formatStateUsing(function (User $row) {
                        return $row->isActive() ? 'Actif' : 'Inactif';
                    }),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(date('d-m-Y') . '- Utilisateurs - export'),
                ]),
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
            ->actions((new UserTableAction)->getActions());
    }



    public function exportToUser()
    {
        return Excel::download(new UserExport(), 'utilisateurs.xlsx');
    }

    public function render()
    {
        return view('livewire.tables.user-table');
    }
}
