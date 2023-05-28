<div class="flex items-center space-x-2">
    @if ($order->canBeUpdated() )
        <div class="flex flex-col" x-data="{ tooltip: 'Modifier' }">
            <button wire:click="confirmOrderUpdate({{ $order->id }})" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </button>
        </div>
       @if($order->is_for_the_evening)
        <div class="flex flex-col" x-data="{ tooltip: 'Programmer pour la journée' }">
            <button wire:click="confirmOrderHourUpdate({{ $order->id }})" wire:loading.attr="disabled"
                x-tooltip="tooltip">
                <x-icon name="sun" class="h-4 w-4 text-secondary-500" />
            </button>
        </div>
        @else
        <div class="flex flex-col" x-data="{ tooltip: 'Programmer pour la nuit' }">
            <button wire:click="confirmOrderHourUpdate({{ $order->id }})" wire:loading.attr="disabled"
                x-tooltip="tooltip">
                <x-icon name="sun-fill" class="h-4 w-4 text-red-800" />
            </button>
        </div>
        @endif
        <div class="flex flex-col" x-data="{ tooltip: 'Annuler' }">
            <button wire:click="confirmOrderCancellation({{ $order->id }})" wire:loading.attr="disabled"
                x-tooltip="tooltip">
                <x-icon name="cross" class="h-4 w-4 text-red-700" />
            </button>
        </div>

    @endif
</div>
