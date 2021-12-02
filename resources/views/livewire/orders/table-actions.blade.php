<div class="flex items-center space-x-2">
    @if ($order->canBeCancelled())
        <div class="flex flex-col" x-data="{ tooltip: 'Modifier' }">
            <button wire:click="confirmOrderUpdate({{ $order->id }})" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </button>
        </div>
        <div class="flex flex-col" x-data="{ tooltip: 'Annuler' }">
            <button wire:click="confirmOrderCancellation({{ $order->id }})" wire:loading.attr="disabled"
                x-tooltip="tooltip">
                <x-icon name="cross" class="h-4 w-4 text-red-700" />
            </button>
        </div>
    @endif
</div>
