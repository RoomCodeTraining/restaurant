<?php

namespace App\Http\Livewire\Orders;

use App\Actions\Order\CreateOrderAction;
use App\Models\Menu;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use App\Support\ActivityHelper;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateSpecialOrderForm extends Component
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

        $this->validate(['selectedDishes' => ['required', 'array']]);

        $dishCount = 0;

        foreach($this->selectedDishes as $menuId => $items) {
            $dishCount += count($items);
        }

        $locket_at = (int) config('cantine.order.locked_at');

        /**
         * S'assurer que l'utilisateur ne passe pas de commande au dela de son quota dejeuner
         */
        if ($this->userAccessCard->quota_lunch < $dishCount) {
            throw ValidationException::withMessages([
              'selectedDishes' => [
                "Vous ne pouvez pas passer plus de " . count($this->$dishCount) . " commande(s) car votre quota dejeuner est de " . $this->userAccessCard->quota_lunch
              ]
            ]);
        }

        /**
         * S'assure qu'aucune commande n'est passée après une certaine heure.
         */
        $todayOrder = $this->menus
          ->filter(fn ($menu) => in_array($menu->id, array_keys($this->selectedDishes)) && $menu->served_at->isCurrentDay())
          ->first();



        if ($todayOrder && now()->hour >= config('cantine.order.locked_at')) {
            throw ValidationException::withMessages([
              'selectedDishes' => [sprintf('Vous ne pouvez commander le menu du jour après %s heures.', config('cantine.order.locked_at'))]
            ]);
        }

        /**
         * verifier si l'utilisateur a deja commande deux plats pour les menus qu'il a choisi
         */
        $previousOrders = Auth::user()->orders()
          ->with('menu')
          ->whereIn('menu_id', array_keys($this->selectedDishes))
          ->whereNotState('state', [Cancelled::class, Suspended::class])
          ->get();

        foreach($this->selectedDishes as $key => $menu) {
            if(count($menu) == 2) {
                throw ValidationException::withMessages([
                  'selectedDishes' => [
                    sprintf('Vous avez déjà commander vos 2 plats pour le menu du %s',  Menu::find($key)->served_at->format('d/m/Y'))
                  ]
                ]);
            }
        }

        dd($previousOrders);

        if (! $previousOrders->isEmpty() && $previousOrders[0]->state != Suspended::class) {
            throw ValidationException::withMessages([
              'selectedDishes' => [
                sprintf('Vous avez déjà commandé le menu du %s', $previousOrders->map(fn ($order) => $order->menu->served_at->format('d/m/Y'))->join(','))
              ]
            ]);
        }

        // if(auth()->user()->hasOrderForToday() && now()->hour < $locket_at && $this->userAccessCard->quota_lunch - 1 < count($this->selectedDishes)) {
        //     throw ValidationException::withMessages([
        //       'selectedDishes' => [
        //         "Vous ne pouvez pas passer plus de ".count($this->selectedDishes)." plats"
        //       ]
        //     ]);
        // }

        /**
         * Crée une commande pour chacun des plats sélectionnés.
         */
        foreach ($this->selectedDishes as $menuId => $dishes) {
            foreach($dishes as $dish) {
                $order = $createOrderAction->execute([
                  'dish_id' => $dish['id'],
                  'menu_id' => $menuId,
                  'user_id' => Auth::id()
                ]);

                ActivityHelper::createActivity(
                    $order,
                    "Création de sa commande du " . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'),
                    "$order->user->full_name vient de passer sa commande du " . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'),
                );
            }
        }

        Notification::make()->title('Commande enregistrée')->body('Votre commande a bien été enregistrée.')->success();

        return redirect()->route('orders.index');
    }

    /**
     * Permet d'ajouter un plat à la commande.
     */
    public function addDish($menuId, $dishId)
    {
        $this->selectedDishes[$menuId][] = $this->menus->firstWhere('id', $menuId)->dishes->firstWhere('id', $dishId);
        $this->resetValidation();
    }

    /**
     * Permet de supprimer un plat de la commande.
     */
    public function removeDish($menuId, $dishId)
    {
        unset($this->selectedDishes[$menuId][$dishId]);
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

        return view('livewire.orders.create-special-order-form', ['menus' => $this->menus]);
    }
}