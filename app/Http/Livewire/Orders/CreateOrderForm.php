<?php

namespace App\Http\Livewire\Orders;

use App\Models\Menu;
use Livewire\Component;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use App\Support\ActivityHelper;
use Illuminate\Support\Facades\Auth;
use App\Actions\Order\CreateOrderAction;
use Illuminate\Validation\ValidationException;

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

    $this->validate(['selectedDishes' => ['required', 'array']]);

    /**
     * S'assure que le quota de commande de l'utilisateur est suffisant.
     */
    if ($this->userAccessCard->quota_lunch === 0) {
      throw ValidationException::withMessages([
        'selectedDishes' => ['Votre quota est insuffisant.']
      ]);
    }


    if($this->userAccessCard->quota_lunch == 1 && auth()->user()->orders->today() && now()->hour < 9){
      throw ValidationException::withMessages([
        'selectedDishes' => [
          "Vous avez une commande du jour en cours et votre quota est de 1. Vous ne pouvez plus effectuer d'autres commandes. Veuillez recharger votre carte"
        ]
      ]);
    }


    /**
     * S'assurer que l'utilisateur n'a pas de commande en cours quant il a des commandes qui sont egal en cours et egal a son quota actuel
     */
 
    if (! auth()->user()->canCreateOtherOrder()) {
      throw ValidationException::withMessages([
        'selectedDishes' => [
          "Impossible de passer d'autres commandes. Veuillez consulter la liste de vos commandes en cours et vérifier votre quota dejeuner."
        ]
      ]);
    }

    /**
     * S'assurer que l'utilisateur ne passe pas de commande au dela de son quota dejeuner
     */
    if ($this->userAccessCard->quota_lunch < count($this->selectedDishes)) {
      throw ValidationException::withMessages([
        'selectedDishes' => [
          "Vous ne pouvez pas passer plus de " . count($this->selectedDishes) . " commande(s) car votre quota dejeuner est de " . $this->userAccessCard->quota_lunch
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
     * S'assure que les plats sélectionnés n'ont pas déjà été commandé par l'utilisateur.
     */
    $previousOrders = Auth::user()->orders()
      ->with('menu')
      ->whereIn('menu_id', array_keys($this->selectedDishes))
      ->whereNotState('state', Cancelled::class)
      ->get();

 
    if (!$previousOrders->isEmpty() && $previousOrders[0]->state != Suspended::class) {
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
     $order =  $createOrderAction->execute([
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

    return view('livewire.orders.create-order-form', ['menus' => $this->menus,]);
  }
}
