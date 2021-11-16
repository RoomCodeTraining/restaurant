<?php

namespace App\Http\Livewire\Orders;

use App\Actions\Order\CreateOrderAction;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateOrderForm extends Component
{
    public $selectedMenuId;

    public $dishId;

    public $selectedMenu;


    public function updated($field, $value)
    {
        if ($field == "selectedMenuId") {
            $this->selectedMenu = Menu::with('starterDish', 'mainDish', 'secondDish', 'dessertDish')->firstWhere('id', $this->selectedMenuId);
        }
    }

    public function saveOrder(CreateOrderAction $createOrderAction)
    {
        $createOrderAction->execute([
            'menu_id' => $this->selectedMenuId,
            'dish_id' => $this->dishId,
            'user_id' => Auth::id()
        ]);

        $this->reset();

        $this->emit('orderSaved');

        session()->flash('success', 'La commande a été effectuée avec succès !');

        return redirect()->route('orders.create');
    }

    public function render()
    {
        return view('livewire.orders.create-order-form', [
            'menus' => Menu::with('starterDish', 'mainDish', 'secondDish', 'dessertDish')->whereBetween('served_at', [now()->startOfWeek(), now()->endOfWeek()])->get(),
        ]);
    }
}
