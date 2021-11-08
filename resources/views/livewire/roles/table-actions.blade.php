<div class="flex items-center">
    @can('user.update')
        <a href="{{ route('roles.edit', $role) }}" class="mr-2" title="Editer">
            <x-icon name="pencil" class="text-secondary-600 h-4 w-4" />
        </a>
    @endcan
    @if ($role->users_count === 0)
        <button wire:click="confirmRoleDeletion({{ $role->id }})" wire:loading.attr="disabled"
            class="mr-2" title="Supprimer">
            <x-icon name="trash" class="text-red-600 h-4 w-4" />
        </button>
    @endif
</div>
