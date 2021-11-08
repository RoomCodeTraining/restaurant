<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingUserDeletion">
    <x-slot name="title">
        Supprimer l'utilisateur
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir supprimer cet utilisateur ?
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>

            <button class="btn btn-error" wire:click="deleteUser" wire:target="deleteUser"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>

<!-- Active User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingUserRestoration">
    <x-slot name="title">
        Restaurer l'utilisateur
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir restaurer cet utilisateur ?
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center">
            <button wire:click="$toggle('confirmingUserRestoration')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-warning" wire:click="restoreUser" wire:target="restoreUser"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
