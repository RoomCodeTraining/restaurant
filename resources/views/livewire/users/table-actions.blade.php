<div class="flex items-center space-x-2">
    <div x-data="{ tooltip: 'Consulter' }">
        <a href="{{ route('users.show', $user) }}" x-tooltip="tooltip">
            <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
        </a>
    </div>
    @hasrole(App\Models\Role::ADMIN)
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('users.edit', $user) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </a>
        </div>
        @if ($user->id !== auth()->user()->id)
            @if ($user->is_active)
                <div x-data="{ tooltip: 'Désactiver' }">
                    <button wire:click="confirmUserLocking({{ $user->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                        <x-icon name="lock-closed" class="h-4 w-4 text-red-700" />
                    </button>
                </div>
            @else
                <div x-data="{ tooltip: 'Activer' }">
                    <button wire:click="confirmUserUnlocking({{ $user->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                        <x-icon name="lock-open" class="h-4 w-4 text-black" />
                    </button>
                </div>
            @endif
            <div x-data="{ tooltip: 'Supprimer' }">
                <button wire:click="confirmUserDeleting({{ $user->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                    <x-icon name="trash" class="h-4 w-4 text-red-900" />
                </button>
            </div>
        @endif
    @endhasrole
</div>
