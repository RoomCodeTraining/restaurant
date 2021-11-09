<div class="flex items-center">
    @can('user.update')
        <a href="{{ route('roles.edit', $role) }}" class="mr-2" title="editer">
            <x-icon name="pencil" class="h4 w-4 text-accent-800" />
        </a>
    @endcan
    @if ($role->users_count === 0)
        <button wire:click="confirmRoleDeletion({{ $role->id }})" wire:loading.attr="disabled"
            class="mr-2" title="Supprimer">
            <x-icon name="trash" class="h4 w-4 text-red-700"/>
        </button>
    @endif
</div>
