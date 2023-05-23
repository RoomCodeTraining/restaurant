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
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingUserLocking')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>

            <button class="btn btn-sm bg-red-500" wire:click="lockUser" wire:target="lockUser"
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
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingUserUnlocking')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-sm bg-secondary-900" wire:click="unlockUser" wire:target="unlockUser"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>


<x-dialog-modal wire:model="confirmingUserDeletion">
    <x-slot name="title">
        Supprimer l'utilisateur
    </x-slot>
    <x-slot name="content">
        Etes vous sûr de vouloir supprimer cet utilisateur ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-sm bg-red-500" wire:click="deleteUser" wire:target="deleteUser"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>

<x-dialog-modal wire:model="confirmingUserLunch">
    <x-slot name="title">
       Pétit dejeuner
    </x-slot>
    <x-slot name="content">
        Etes vous sûr de vouloir autoriser ce utilisateur de prendre le petit dejeuner ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-gray-900" wire:click="$toggle('confirmingUserLunch')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-sm bg-red-500" wire:click="confirmLunch()" wire:target="confirmLunch()"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
