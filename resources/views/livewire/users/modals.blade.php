<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingUserLocking">
    <x-slot name="title">
        Désactiver l'utilisateur
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir désactiver cet utilisateur ?
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingUserLocking')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>

            <button class="btn btn-error" wire:click="lockUser" wire:target="lockUser"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>

<!-- Active User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingUserUnlocking">
    <x-slot name="title">
        Réactiver l'utilisateur
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir réactiver cet utilisateur ?
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingUserUnlocking')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-warning" wire:click="unlockUser" wire:target="unlockUser"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
