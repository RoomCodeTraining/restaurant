<x-dialog-modal wire:model="showingDetails" maxWidth="3xl">
    <x-slot name="title">
        Le details des commandes de l'utilisateur
    </x-slot>

    <x-slot name="content">
        <x-table hover no-shadow :columns="[
                'Menu du' => fn ($order) => $order->menu->served_at->format('d/m/Y'),
                'Plat' => fn ($order) => $order->dish->name,
            ]" :rows="$orders">
        </x-table>
    </x-slot>

    <x-slot name="footer">
        <div class="inline-flex items-center space-x-2">
            <button class="btn btn-sm bg-red-500 text-white" wire:click="$toggle('showingDetails')" wire:loading.attr="disabled">
                {{ __('Fermer') }}
            </button>
        </div>
    </x-slot>
</x-dialog-modal>
