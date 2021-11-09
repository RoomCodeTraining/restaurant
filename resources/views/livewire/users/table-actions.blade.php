<div class="flex items-center space-x-2">
    <a href="{{ route('users.show', $user) }}">
        <x-icon name="eye" />
    </a>
    @hasrole(App\Models\Role::ADMIN)
        <a href="{{ route('users.edit', $user) }}">
            <x-icon name="pencil" />
        </a>
        @if ($user->id !== auth()->user()->id)
            @if ($user->is_active)
                <button wire:click="confirmUserLocking({{ $user->id }})" wire:loading.attr="disabled">
                    <x-icon name="lock-closed" />
                </button>
                @else
                <button wire:click="confirmUserUnlocking({{ $user->id }})" wire:loading.attr="disabled">
                    <x-icon name="lock-open" />
                </button>
            @endif
        @endif
    @endhasrole
</div>
