<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingOrganizationDeletion">
    <x-slot name="title">
        Supprimer la société
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir supprimer cette société ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingOrganizationDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-error" wire:click="deleteOrganization" wire:target="deleteOrganization"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>


