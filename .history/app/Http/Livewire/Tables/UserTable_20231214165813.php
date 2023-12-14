<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Livewire\Component;
use Filament\Tables\Table;
use App\Exports\UserExport;
use App\Exports\QuotaExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Tables\Actions\UserTableAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

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
                TextColumn::make('contact')->label('Contact'),
                TextColumn::make('role.name')->label('Profil'),
                TextColumn::make('is_active')
                    ->label('Etat du compte')
                    ->badge()
                    ->color(fn (User $row) => $row->is_active ? 'success' : 'danger')
                    ->formatStateUsing(function (User $row) {
                        if ($row->is_active == 1)
                            return 'actif';
                    }),

                TextColumn::make('id')
                    ->hidden()
                    ->label('Numéro de carte NFC')
                    ->formatStateUsing(fn (User $record) => $record->currentAccessCard->identifier ?? "Aucune carte associée"),

                TextColumn::make('created_at')->formatStateUsing(fn (User $record) => $record->accessCard ? $record->accessCard->breakfast_reload_count : "Aucun rechargement")
                    ->label('Réchargement petit dejeuner')
                    ->hidden(),

                TextColumn::make('updated_at')->formatStateUsing(fn (User $record) => $record->accessCard ? $record->accessCard->lunch_reload_count : "Aucun rechargement")
                    ->label('Réchargement déjeuner')
                    ->hidden()

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
        // ->bulkActions([
        //     BulkAction::make('export')->label('Exporter')
        //         ->action(function (Collection $record) {
        //             return Excel::download(new UserExport(), now()->format('d-m-Y') . ' Liste-Utilisateurs.xlsx');
        //         }),

        //     BulkAction::make('edit')->label('Exporter le Qota')
        //         ->action(function (Collection $records) {
        //             return Excel::download(new QuotaExport(), now()->format('d-m-Y') . ' Quota-Utilisateurs.xlsx');
        //         })
        // ]);
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
