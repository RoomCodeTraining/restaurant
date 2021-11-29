<?php

namespace App\Http\Livewire\Orders;

use App\Actions\Order\CreateOrderAction;
use App\Models\Menu;
use App\States\Order\Cancelled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * Formulaire de création d'une commande.
 *
 * @package App\Http\Livewire\Orders
 */
class CreateOrderForm extends Component
{
    /**
     * Les menus de la semaine.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $menus;

    /**
     * Les plats choisis par l'utilisateur.
     *
     * @var array
     */
    public $selectedDishes = [];

    /**
     * La carte de l'utilisateur qui sera utilisée pour la commande.
     *
     * @var \App\Models\AccessCard
     */
    public $userAccessCard;

    public function mount()
    {
        $this->userAccessCard = Auth::user()->accessCard;
    }

    /**
     * Une fois que le formulaire est soumis, on crée la commande.
     *
     * @return void
     */
    public function saveOrder(CreateOrderAction $createOrderAction)
    {
        $this->resetErrorBag();

        $this->validate([ 'selectedDishes' => ['required', 'array'] ]);

        /**
         * S'assure que le quota de commande de l'utilisateur est suffisant.
         */
        if ($this->userAccessCard->quota_lunch === 0) {
            throw ValidationException::withMessages([
                'selectedDishes' => ['Vous quota est insuffisant.']
            ]);
        }

        /**
         * S'assure qu'aucune commande n'est passée après une certaine heure.
         */
        $todayMenu = $this->menus
            ->filter(fn ($menu) => in_array($menu->id, array_keys($this->selectedDishes)) && $menu->served_at->isCurrentDay())
            ->first();

        if ($todayMenu && now()->hour >= config('cantine.order.order_before')) {
            throw ValidationException::withMessages([
                'selectedDishes' => [sprintf('Vous ne pouvez commander le menu du jour après %s heures.', config('cantine.order.order_before'))]
            ]);
        }

        /**
         * S'assure que les plats sélectionnés n'ont pas déjà été commandé par l'utilisateur.
         */
        $previousOrders = Auth::user()->orders()
            ->with('menu')
            ->whereIn('menu_id', array_keys($this->selectedDishes))
            ->whereNotState('state', Cancelled::class)
            ->get();

        if (! $previousOrders->isEmpty()) {
            throw ValidationException::withMessages([
                'selectedDishes' => [
                    sprintf('Vous avez déjà commandé le menu du %s', $previousOrders->map(fn ($order) => $order->menu->served_at->format('d/m/Y'))->join(','))
                ]
            ]);
        }

        /**
         * Crée une commande pour chacun des plats sélectionnés.
         */
        foreach ($this->selectedDishes as $menuId => $dish) {
            $createOrderAction->execute([
                'dish_id' => $dish['id'],
                'menu_id' => $menuId,
                'user_id' => Auth::id()
            ]);
        }

        session()->flash('success', 'La commande a été effectuée avec succès !');

        return redirect()->route('orders.index');
    }

    /**
     * Permet d'ajouter un plat à la commande.
     */
    public function addDish($menuId, $dishId)
    {
        $this->selectedDishes[$menuId] = $this->menus->firstWhere('id', $menuId)->dishes->firstWhere('id', $dishId);
        $this->resetValidation();
    }

    /**
     * Permet de supprimer un plat de la commande.
     */
    public function removeDish($menuId)
    {
        unset($this->selectedDishes[$menuId]);
        $this->resetValidation();
    }

    public function messages()
    {
        return [
            'dishes.required' => 'Vous devez choisir au moins un plat',
        ];
    }

    public function render()
    {
        $this->menus = Menu::with('dishes.dishType')
            ->whereBetween('served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        return view('livewire.orders.create-order-form', [ 'menus' => $this->menus, ]);
    }
}
