<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingPaymentMethodDeletion">
    <x-slot name="title">
        Supprimer la méthode de paiement
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir supprimer cette méthode de paiement ?
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingPaymentMethodDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>

            <button class="btn btn-sm bg-red-500" wire:click="deletePaymentMethod" wire:target="deleteMenu"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
