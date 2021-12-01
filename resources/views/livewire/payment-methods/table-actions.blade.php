<div class="flex items-center space-x-2">
    @can('update', $paymentMethod)
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('paymentMethods.edit', $paymentMethod) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </a>
        </div>
    @endcan
    @can('delete', $paymentMethod)
        <div x-data="{ tooltip: 'Supprimer' }">
            <button wire:click="confirmPaymentMethodDeletion({{ $paymentMethod->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                <x-icon name="trash" class="h-4 w-4 text-red-700" />
            </button>
        </div>
    @endcan
</div>
