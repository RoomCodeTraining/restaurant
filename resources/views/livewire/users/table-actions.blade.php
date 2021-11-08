<div class="flex items-center">
    @if ($user->deleted_at !== null)
        <button wire:click="confirmUserRestoration({{ $user->id }})" wire:loading.attr="disabled">
            <svg id="restore-{{ $user->id }}" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 17l-2 2l2 2m-2-2h9a2 2 0 0 0 1.75-2.75l-.55-1"></path>
                    <path d="M8.536 11l-.732-2.732L5.072 9m2.732-.732l-5.5 7.794a2 2 0 0 0 1.506 2.89l1.141.024"></path>
                    <path d="M15.464 11l2.732.732L18.928 9m-.732 2.732l-5.5-7.794a2 2 0 0 0-3.256-.14l-.591.976"></path>
                </g>
            </svg>
        </button>
        <x-tooltip content="Restaurer" placement="top" append-to="#restore-{{ $user->id }}" />
    @else
        <a href="{{ route('users.show', $user) }}" class="mr-2">
            <x-icon name="eye" />
        </a>
        @can('user.update')
            <a href="{{ route('users.edit', $user) }}" class="mr-2">
                <x-icon name="pencil" />
            </a>
        @endcan
        @if ($user->id !== auth()->user()->id && !$user->isSuperAdmin())
            <button wire:click="confirmUserDeletion({{ $user->id }})" wire:loading.attr="disabled"
                class="mr-2">
                <x-icon name="trash" />
            </button>
        @endif
    @endif
</div>
