<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingOrderCancellation">
    <x-slot name="title">
        Annuler la commande
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de annuler vouloir votre commande ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingOrderCancellation')"
                wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-sm bg-red-500" wire:click="cancelOrder" wire:target="cancelOrder"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>

<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingOrderUpdate">
    <x-slot name="title">
        Modifier la commande
    </x-slot>
    <x-slot name="content">
        <div class="col-span-8 md:col-span-4">
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text">Plat principal</span>
                </label>
                @if ($selectedOrder)
                    <select class="select select-bordered w-full" wire:model.defer="dishId">
                        <option value="{{ null }}" selected="selected">Veuillez choisir</option>
                        <option value="{{ $selectedOrder->menu->main_dish->id }}">
                            {{ $selectedOrder->menu->main_dish->name }}
                        </option>
                        <option value="{{ $selectedOrder->menu->second_dish?->id }}">
                            {{ $selectedOrder->menu->second_dish?->name }}
                        </option>
                    </select>
                @endif
            </div>
            @error('dishId')
                <label class="label">
                    <span class="label-text-alt text-red-600">{{ $message }}</span>
                </label>
            @enderror
        </div>
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingOrderUpdate')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-sm bg-secondary-900" wire:click="updateOrder" wire:target="updateOrder"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
<x-dialog-modal wire:model="confirmingOrderHourUpdate">
    <x-slot name="title">
        Modifier l'heure de consommation
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir modifier l'heure de consommation de votre commande ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingOrderHourUpdate')"
                wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-sm bg-red-500" wire:click="updateHour" wire:target="updateHour"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
