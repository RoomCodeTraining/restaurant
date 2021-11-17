<!-- Delete User Confirmation Modal -->
<x-dialog-modal wire:model="confirmingModelDeletion">
    <x-slot name="title">
        Supprimer la catégorie
    </x-slot>

    <x-slot name="content">
        Etes vous sûr de vouloir supprimer cette catégorie professionnelle ?
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button wire:click="$toggle('confirmingModelDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </button>
            <button class="btn btn-error" wire:click="deleteModel" wire:target="deleteModel"
                wire:loading.attr="disabled" wire:loading.class="loading">
                {{ __('Confirmer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
