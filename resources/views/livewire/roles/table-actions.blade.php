<div class="flex items-center">
    @can('user.update')
        <a href="{{ route('roles.edit', $role) }}" class="mr-2">
            <x-icon name="pencil" />
        </a>
    @endcan
    @if ($role->users_count === 0)
        <button wire:click="confirmRoleDeletion({{ $role->id }})" wire:loading.attr="disabled"
            class="mr-2">
            <x-icon name="trash" />
        </button>
    @endif
</div>
