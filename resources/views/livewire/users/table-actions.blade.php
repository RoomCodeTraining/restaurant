<div class="flex items-center space-x-2">
    <a href="{{ route('users.show', $user) }}" title="Détails">
        <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
    </a>
    @hasrole(App\Models\Role::ADMIN)
        <a href="{{ route('users.edit', $user) }}" title="Editer">
            <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
        </a>
        @if ($user->id !== auth()->user()->id)
            @if ($user->is_active)
                <button wire:click="confirmUserLocking({{ $user->id }})" wire:loading.attr="disabled">
                    <x-icon name="lock-closed" class="h-4 w-4 text-red-700" />
                </button>
                @else
                <button wire:click="confirmUserUnlocking({{ $user->id }})" wire:loading.attr="disabled">
                    <x-icon name="lock-open" class="h-4 w-4 text-black" />
                </button>
            @endif
        @endif
    @endhasrole
</div>
