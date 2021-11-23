<?php

namespace App\Http\Livewire\Orders;

use App\Actions\Order\CreateOrderAction;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateOrderForm extends Component
{
    public $dishes = [];

    public $menus = [];

    public $selectedDishes = [];

    public $userAccessCard;

    public function mount()
    {
        $this->userAccessCard = Auth::user()->accessCard;
    }

    public function saveOrder(CreateOrderAction $createOrderAction)
    {
        $this->validate([ 'selectedDishes' => ['required'] ]);

        if ($this->userAccessCard->quota_lunch === 0) {
            throw ValidationException::withMessages([
                'selectedDishes' => ['Vous quota de est bas.']
            ]);
        }

        $previousOrders = Auth::user()->orders()->whereIn('menu_id', array_keys($this->selectedDishes))->get();

        if ($previousOrders->count() > 0) {
            throw ValidationException::withMessages([
                'selectedDishes' => ['Votre panier contient un menu que vous avez déjà commandé.']
            ]);
        }

        // now()->lessThanOrEqualTo()

        foreach ($this->selectedDishes as $menuId => $dish) {
            $createOrderAction->execute([
                'dish_id' => $dish['id'],
                'menu_id' => $menuId,
                'user_id' => Auth::id()
            ]);
        }

        $this->reset();

        session()->flash('success', 'La commande a été effectuée avec succès !');

        return redirect()->route('orders.index');
    }

    public function messages()
    {
        return [
            'dishes.required' => 'Vous devez choisir au moins un plat',
        ];
    }

    public function render()
    {
        $this->menus = Menu::with('starterDish', 'mainDish', 'secondDish', 'dessertDish')
            ->whereBetween('served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        return view('livewire.orders.create-order-form', [
            'menus' => $this->menus,
        ]);
    }

    public function addDish($menuId, $dish)
    {
        $this->selectedDishes[$menuId] = $dish;
    }

    public function removeDish($menuId)
    {
        unset($this->selectedDishes[$menuId]);
    }
}
