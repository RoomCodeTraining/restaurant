<div class="flex items-center space-x-2">
    <a href="{{ route('dishes.show', $dish) }}">
        <x-icon name="eye" />
    </a>
    @can('menu.update')
        <a href="{{ route('dishes.edit', $dish) }}">
            <x-icon name="pencil" />
        </a>
    @endcan
    @can('menu.delete')
        <button wire:click="confirmDishDeletion({{ $dish->id }})" wire:loading.attr="disabled">
            <x-icon name="trash" />
        </button>
    @endcan
</div>
