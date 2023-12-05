<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\PaymentMethod;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class PaymentMethodTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        dd(\App\Models\PaymentMethod::query()->with('access_cards')->get());
        return $table
            ->query(\App\Models\PaymentMethod::query()->with('access_cards'))
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM'),
                TextColumn::make('id')->label('DESCRIPTION')
                    ->formatStateUsing(fn ($record) => $record->description ? $record->description : 'Aucune description'),
            ])->actions([
                ActionGroup::make([
                    Action::make('Editer')
                        ->url(fn (PaymentMethod $record): string => route('paymentMethods.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (PaymentMethod $record) {
                            //DepartmentDeleted::dispatch($record);
                            Notification::make()->title('Méthode de paiement supprimé avec succès !')->danger()->send();

                            return redirect()->route('paymentMethods.index');
                        })
                        ->hidden(fn (PaymentMethod $record) => $record->accessCards->count() == 0)
                        > action(fn (PaymentMethod $record) => $record->delete()),

                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.payment-method-table');
    }
}
