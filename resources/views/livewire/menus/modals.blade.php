<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingMenuDeletion">
    <x-slot name="title">
        Supprimer le menu
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir supprimer ce menu ?
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingMenuDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>

            <button class="btn-sm btn-error" wire:click="deleteMenu" wire:target="deleteMenu"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>


