<div class="flex items-center space-x-2">
    <a href="{{ route('menus.show', $menu) }}">
        <x-icon name="eye" />
    </a>
    @can('menu.update')
        <a href="{{ route('menus.edit', $menu) }}">
            <x-icon name="pencil" />
        </a>
    @endcan
    @can('menu.delete')
        <button wire:click="confirmMenuDeletion({{ $menu->id }})" wire:loading.attr="disabled">
            <x-icon name="trash" />
        </button>
    @endcan
</div>
