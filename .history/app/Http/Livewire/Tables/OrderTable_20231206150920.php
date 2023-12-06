<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use App\Support\ActivityHelper;
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
                TextColumn::make('created_at')->label('EFFECTUÉ LE')->dateTime('d/m/Y'),
                TextColumn::make('menu.served_at')->label('MENU DU ')->searchable()->dateTime('d/m/Y'),
                TextColumn::make('dish.name')->label('PLAT'),
                TextColumn::make('is_for_the_evening')->label('COMMANDE')
                    ->formatStateUsing(fn (Order $record) => view('livewire.orders.hour', ['order' => $record])),

                TextColumn::make('state')->label('Statut')
                    ->formatStateUsing(fn (Order $record) => view('livewire.orders.state', ['order' => $record])),
                TextColumn::make('id')->label('Actions')->formatStateUsing(fn (Order $row) => view('livewire.orders.table-actions', ['order' => $row])),

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
        session()->flash('success', "Votre commande a été modifiée avec succès !");

        return redirect()->route('orders.index');
    }

    public function cancelOrder()
    {
        $order = Order::find($this->orderIdBeingCancelled);

        $order->state->transitionTo(Cancelled::class);

        $this->confirmingOrderCancellation = false;
        ActivityHelper::createActivity(
            $order,
            'Annulation de la commande du ' . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'),
            'Annulation de la commande',
        );
        session()->flash('success', "Votre commande a été annulée avec succès !");

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

        session()->flash('success', "Votre commande a été modifiée avec succès !");

        return redirect()->route('orders.index');
    }
    // public function render()
    // {
    //     return view('livewire.tables.order-table');
    // }
}
