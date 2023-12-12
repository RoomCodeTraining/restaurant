<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use App\States\Order\Cancelled;
use App\States\Order\Confirmed;
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

class OrderTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public $orderIdBeingCancelled;
    public $confirmingOrderCancellation = false;

    public $orderIdHourBeingUpdated;
    public $confirmingOrderHourUpdate = false;

    public $dishId;
    public $selectedOrder;
    public $orderIdBeingUpdated;
    public $confirmingOrderUpdate = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Order::query())
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Effectué le'))
                    ->dateTime('d/m/Y'),
                TextColumn::make('menu.served_at')
                    ->label(__('Menu du'))
                    ->searchable()
                    ->dateTime('d/m/Y'),
                TextColumn::make('dish.name')->label(__('Plat')),
                TextColumn::make('is_for_the_evening')
                    ->label(__('Type de commande'))
                    ->badge()

                    ->color(fn (Order $record) => !$record->is_for_the_evening ? 'gray' : 'primary')
                    ->formatStateUsing(fn (Order $record) => !$record->is_for_the_evening ? 'Commande de midi' : 'Commande du soir')
                    ->icon(fn (Order $record) => !$record->is_for_the_evening ? 'heroicon-o-sun' : 'heroicon-o-moon'),
                TextColumn::make('state')
                    ->label('Statut')
                    ->badge()
                    ->color(function (Order $record) {
                        if ($record->isCurrentState(Confirmed::class)) {
                            return 'gray';
                        } elseif ($record->isCurrentState(Cancelled::class)) {
                            return 'danger';
                        } else {
                            return 'success';
                        }
                    })
                    ->formatStateUsing(fn (Order $record) => $record->state->title()),
            ])
            ->actions([
                // Action::make('Editer')
                //     ->url(fn (Order $record): string => route('orders.edit', $record))
                //     ->icon('heroicon-o-pencil'),

                Action::make('delete')
                    ->label('')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->modalHeading('Annulation de la commande')
                    ->modalDescription('Êtes-vous sûr de vouloir annuler cette commande ?')
                    ->requiresConfirmation()
                    ->tooltip('Annuler la commande')
                    ->hidden(fn (Order $record) => !$record->isCurrentState(Confirmed::class))
                    ->action(function (Order $record) {
                        $record->state->transitionTo(Cancelled::class);
                        ActivityHelper::createActivity($record, 'Annulation de la commande du ' . \Carbon\Carbon::parse($record->menu->served_at)->format('d-m-Y'), 'Annulation de la commande');

                        Notification::make()
                            ->title('Commande annulée')
                            ->success()
                            ->body('Votre commande du ' . \Carbon\Carbon::parse($record->menu->served_at)->format('d-m-Y') . ' a été annulée.')
                            ->icon('heroicon-o-x-mark')
                            ->send($record->user);
                    }),
            ]);
    }

    public function confirmOrderCancellation($orderId)
    {
        $this->orderIdBeingCancelled = $orderId;
        $this->confirmingOrderCancellation = true;
    }

    public function confirmOrderHourUpdate($orderId)
    {
        $this->orderIdHourBeingUpdated = $orderId;
        $this->confirmingOrderHourUpdate = true;
    }

    public function updateHour()
    {
        $order = Order::find($this->orderIdHourBeingUpdated);
        if ($order->is_for_the_evening) {
            $order->update(['is_for_the_evening' => false]);
        } else {
            $order->update(['is_for_the_evening' => true]);
        }
        $this->confirmingOrderHourUpdate = false;
        session()->flash('success', 'Votre commande a été modifiée avec succès !');

        return redirect()->route('orders.index');
    }

    public function cancelOrder()
    {
        $order = Order::find($this->orderIdBeingCancelled);

        $order->state->transitionTo(Cancelled::class);

        $this->confirmingOrderCancellation = false;
        ActivityHelper::createActivity($order, 'Annulation de la commande du ' . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'), 'Annulation de la commande');
        session()->flash('success', 'Votre commande a été annulée avec succès !');

        return redirect()->route('orders.index');
    }

    public function confirmOrderUpdate($orderId)
    {
        $this->orderIdBeingUpdated = $orderId;
        $this->selectedOrder = Order::with('user')->find($orderId);
        $this->dishId = $this->selectedOrder->dish_id;
        $this->confirmingOrderUpdate = true;
    }

    public function updateOrder()
    {
        $order = Order::find($this->orderIdBeingUpdated);

        $order->update(['dish_id' => $this->dishId]);

        if ($order->state->canTransitionTo(Confirmed::class)) {
            $order->state->transitionTo(Confirmed::class);
        }

        $this->confirmingOrderUpdate = false;

        session()->flash('success', 'Votre commande a été modifiée avec succès !');

        return redirect()->route('orders.index');
    }
    // public function render()
    // {
    //     return view('livewire.tables.order-table');
    // }
}