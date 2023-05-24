<div class="flex items-center space-x-2">
    @if (auth()->user()->can('manage', \App\Models\Menu::class))
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('menus-specials.edit', $menuSpecial) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </a>
        </div>

        <div x-data="{ tooltip: 'Supprimer' }">
            <button wire:click="confirmMenuDeletion({{ $menuSpecial->id }})" wire:loading.attr="disabled"
                x-tooltip="tooltip">
                <x-icon name="trash" class="h-4 w-4 text-red-700" />
            </button>
        </div>
    @endif
</div>
