<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingUserTypeDeletion">
    <x-slot name="title">
        Supprimer le type d'utilisateur
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir supprimer cet type d'utilisateur ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingUserTypeDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-error" wire:click="deleteUserType" wire:target="deleteUserType"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
