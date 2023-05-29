<div class="flex items-center">

        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('roles.edit', $role) }}" class="mr-2" x-tooltip="tooltip">
                <x-icon name="pencil" class="h4 w-4 text-accent-800" />
            </a>
        </div>
    @if ($role->users_count === 0)
        <div x-data="{ tooltip: 'Supprimer' }">
            <button wire:click="confirmRoleDeletion({{ $role->id }})" wire:loading.attr="disabled"
                class="mr-2" x-tooltip="tooltip">
                <x-icon name="trash" class="h4 w-4 text-red-700" />
            </button>
        </div>
    @endif
</div>
