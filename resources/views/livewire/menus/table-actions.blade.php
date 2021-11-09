<div class="flex items-center space-x-2">
    <a href="{{ route('menus.show', $menu) }}">
        <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
    </a>
    @can('menu.update')
        <a href="{{ route('menus.edit', $menu) }}" title="Editer">
            <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
        </a>
    @endcan
    @can('menu.delete')
        <button wire:click="confirmMenuDeletion({{ $menu->id }})" wire:loading.attr="disabled" title="Supprimer">
            <x-icon name="trash" class="h-4 w-4 text-red-700"/>
        </button>
    @endcan
</div>
