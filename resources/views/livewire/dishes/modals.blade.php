<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingDishDeletion">
    <x-slot name="title">
        Supprimer le plat
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir supprimer ce plat ?
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingDishDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>

            <button class="btn btn-error" wire:click="deleteDish" wire:target="deleteDish"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>


