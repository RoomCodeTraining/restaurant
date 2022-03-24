<x-dialog-modal wire:model="showingUsers" maxWidth="3xl" class="customScroll">
    <x-slot name="title">
        Utilisateurs ayant commandés ce plat
    </x-slot>
    <x-slot name="content">
        <x-table hover no-shadow :columns="[
                'Matricule' => 'identifier',
                'Nom' => 'full_name',
            ]" :rows="$users">
        </x-table>
    </x-slot>
    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-red-500 text-white" wire:click="$toggle('showingUsers')" wire:loading.attr="disabled">
                {{ __('Fermer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
