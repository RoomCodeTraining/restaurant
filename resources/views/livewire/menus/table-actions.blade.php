<div class="flex items-center space-x-2">
    <div x-data="{ tooltip: 'Consulter' }">
        <a href="{{ route('menus.show', $menu) }}" x-tooltip="tooltip">
            <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
        </a>
    </div>
    @can('menu.update')
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('menus.edit', $menu) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </a>
        </div>
    @endcan
    @can('menu.delete')
        <div x-data="{ tooltip: 'Supprimer' }">
            <button wire:click="confirmMenuDeletion({{ $menu->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                <x-icon name="trash" class="h-4 w-4 text-red-700" />
            </button>
        </div>
    @endcan
</div>
