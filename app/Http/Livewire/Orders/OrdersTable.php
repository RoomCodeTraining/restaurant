<?php

namespace App\Http\Livewire\Orders;

use App\Models\Order;
use App\States\Order\Cancelled;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\ActivityHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class OrdersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $orderIdBeingCancelled;
    public $confirmingOrderCancellation = false;

    public $orderIdHourBeingUpdated;
    public $confirmingOrderHourUpdate = false;

    public $dishId;
    public $selectedOrder;
    public $orderIdBeingUpdated;
    public $confirmingOrderUpdate = false;

    public array $filterNames = [
        'start_date' => 'A partir du',
        'end_date' => "Jusqu'au",
        'state' => "Statut",
    ];

    public function mount()
    {
        $this->filters['start_date'] = now()->startOfWeek()->format('Y-m-d');
        $this->filters['end_date'] = now()->endOfWeek()->format('Y-m-d');
    }

    public function columns(): array
    {
        return [
            Column::make('Effectué le', 'created_at')->format(fn ($row) => $row->format('d/m/Y'))->sortable(),
            Column::make('Menu du', 'menu.served_at')->format(fn ($row) => $row->format('d/m/Y'))->sortable(function (Builder $query, $direction) {
                return $query->whereHas('menu', function ($query) use ($direction) {
                    $query->orderBy('served_at', $direction);
                });
            }),
            Column::make('Plat', 'dish.name')->searchable(function (Builder $query, $searchTerm) {
                return $query->whereHas('dish', function ($query) use ($searchTerm) {
                    $query->orWhere('name', $searchTerm);
                });
            }),
            Column::make('Commande ', 'is_for_the_evening')->format(fn ($val, $col, Order $row) => view('livewire.orders.hour', ['order' => $row])),
            Column::make('Statut')->format(fn ($val, $col, Order $row) => view('livewire.orders.state', ['order' => $row])),
            Column::make('Actions')->format(fn ($val, $col, Order $row) => view('livewire.orders.table-actions', ['order' => $row])),
        ];
    }

    public function filters(): array
    {
        return [
            'start_date' => Filter::make('A partir du')->date(),
            'end_date' => Filter::make("Jusqu'au")->date(),
            'state' => Filter::make('Statut')->select([
                '' => 'Tous',
                Confirmed::$name => Confirmed::title(),
                Cancelled::$name => Cancelled::title(),
                Completed::$name => Completed::title(),
            ]),
        ];
    }

    public function query(): Builder
    {
        return Order::with('user', 'menu', 'dish')
            ->where('user_id', auth()->id())
            ->when($this->getFilter('state'), fn ($query, $state) => $query->where('state', $state))
            ->when($this->getFilter('start_date'), fn ($query, $startDate) => $query->whereDate('created_at', '>=', $startDate))
            ->when($this->getFilter('end_date'), fn ($query, $endDate) => $query->whereDate('created_at', '<=', $endDate));
    }

    public function modalsView(): string
    {
        return 'livewire.orders.modals';
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
        if($order->is_for_the_evening) {
            $order->update(['is_for_the_evening' => false]);
        } else {
            $order->update([ 'is_for_the_evening' => true ]);
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

        $order->update([ 'dish_id' => $this->dishId ]);

        if ($order->state->canTransitionTo(Confirmed::class)) {
            $order->state->transitionTo(Confirmed::class);
        }

        $this->confirmingOrderUpdate = false;

        session()->flash('success', "Votre commande a été modifiée avec succès !");

        return redirect()->route('orders.index');
    }
}
