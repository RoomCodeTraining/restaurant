<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingOrderCancellation">
    <x-slot name="title">
        Annuler la commande
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir votre commande ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm" wire:click="$toggle('confirmingOrderCancellation')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-sm btn-error" wire:click="cancelOrder" wire:target="cancelOrder"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
