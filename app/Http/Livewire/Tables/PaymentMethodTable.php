<?php

namespace App\Http\Livewire\Tables;

use App\Models\PaymentMethod;
use App\Support\ActivityHelper;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class PaymentMethodTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\PaymentMethod::query()->withCount('accessCards')->latest())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date de création')
                    ->searchable()
                    ->dateTime('d/m/Y H:i:s'),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('id')
                    ->label('Description')
                    ->formatStateUsing(fn ($record) => $record->description ? $record->description : 'Aucune description'),
            ])
            ->actions([
                Action::make('edit')->label('')
                    ->tooltip('Editer le moyen de paiement')
                    ->url(fn (PaymentMethod $record): string => route('paymentMethods.edit', $record))
                    ->icon('heroicon-o-pencil-square')
                    ->color('info'),
                Action::make('Supprimer')
                    ->requiresConfirmation()
                    ->label('')
                    ->tooltip('Supprimer le moyen de paiement')
                    ->icon('heroicon-o-trash')
                    ->modalHeading('Suppression du moyen de paiement')
                    ->modalDescription('Êtes-vous sûr de vouloir supprimer ce moyen de paiement ?')
                    ->color('danger')
                    ->hidden(fn (PaymentMethod $record) => $record->access_cards_count > 0)
                    ->action(function (PaymentMethod $record) {
                        $record->delete();
                        ActivityHelper::createActivity($record, 'Suppression du moyen de paiement ' . $record->name, 'Suppression du moyen de paiement');
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.payment-method-table');
    }
}