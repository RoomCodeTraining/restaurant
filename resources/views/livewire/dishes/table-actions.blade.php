<div class="flex items-center space-x-2">
    <div x-data="{ tooltip: 'Consulter' }">
        <a href="{{ route('dishes.show', $dish) }}" x-tooltip="tooltip">
            <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
        </a>
    </div>
    @can('menu.update')
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('dishes.edit', $dish) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </a>
        </div>
    @endcan
    @can('menu.delete')
        <div x-data="{ tooltip: 'Supprimer' }">
            <button wire:click="confirmDishDeletion({{ $dish->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                <x-icon name="trash" class="h-4 w-4 text-red-700" />
            </button>
        </div>
    @endcan
</div>
