<div class="flex items-center space-x-2">
    <div x-data="{ tooltip: 'Consulter' }">
        <a href="{{ route('menus.show', $menu) }}" x-tooltip="tooltip">
            <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
        </a>
    </div>
    @if (auth()->user()->can('manage', \App\Models\Menu::class))
       @if(!$menu->isOldMenu())
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('menus.edit', $menu) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </a>
        </div>
        @endif
        @if ($menu->orders_count === 0)
            <div x-data="{ tooltip: 'Supprimer' }">
                <button wire:click="confirmMenuDeletion({{ $menu->id }})" wire:loading.attr="disabled"
                    x-tooltip="tooltip">
                    <x-icon name="trash" class="h-4 w-4 text-red-700" />
                </button>
            </div>
        @endif
    @endif
</div>
