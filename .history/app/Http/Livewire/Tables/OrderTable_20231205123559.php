<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class OrderTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Order::query())
            ->columns([
                TextColumn::make('created_at')->label('EFFECTUÉ LE')->dateTime('d/m/Y')->sortable(),
                TextColumn::make('menu.served_at')->label('MENU DU ')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('dish.name')->label('PLAT'),
                TextColumn::make('is_for_the_evening')->label('COMMANDE')
                    ->formatStateUsing(fn (Order $record) => view('livewire.orders.hour', ['order' => $record])),

                TextColumn::make('state')->label('Statut')
                    ->formatStateUsing(fn (Order $record) => view('livewire.orders.state', ['order' => $record])),
                TextColumn::make('id')->formatStateUsing(fn (Order $row) => view('livewire.orders.table-actions', ['order' => $row])),
                //TextColumn::make('id')->label('Actions')->format(fn (Order $record) => view('livewire.orders.table-actions', ['order' => $record])),
            ]);
        // ->actions([
        //     ActionGroup::make([
        //         Action::make('Editer')
        //             ->url(fn (Order $record): string => route('orders.edit', $record))
        //             ->icon('heroicon-o-pencil'),

        //         Action::make('Supprimer')
        //             ->requiresConfirmation()
        //             ->icon('heroicon-o-trash')
        //             ->color('danger')
        //             ->before(function (Order $record) {
        //                 //DepartmentDeleted::dispatch($record);
        //                 Notification::make()->title('Commande annuler avec succès !')->danger()->send();
        //                 return redirect()->route('orders.index');
        //             })
        //             //->hidden(fn (Order $record) => $record->users->count() > 0)
        //             ->action(fn (Order $record) => $record->delete()),

        //     ]),
        // ]);
    }
    public function render()
    {
        return view('livewire.tables.order-table');
    }
}
