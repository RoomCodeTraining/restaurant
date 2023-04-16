<div class="flex items-center space-x-2">
    <div x-data="{ tooltip: 'Consulter' }">
        <a href="{{ route('users.show', $user) }}" x-tooltip="tooltip">
            <x-icon name="eye" class="h-4 w-4 text-secondary-800" />
        </a>
    </div>
    @if(auth()->user()->hasRole(App\Models\Role::ADMIN_RH) && $user->accessCard)
    <div x-data="{ tooltip: 'Recharger sa carte' }">
      <a href="{{ route('reload.card', $user->accessCard) }}" x-tooltip="tooltip">
          <x-icon name="card" class="h-4 w-4 text-primary-800" />
      </a>
    </div>
    @endif
    @hasrole(App\Models\Role::ADMIN)
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('users.edit', $user) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
            </a>
        </div>
        @if ($user->id !== auth()->user()->id)
            @if ($user->is_active)
                <div x-data="{ tooltip: 'Désactiver' }">
                    <a href='#' wire:click="confirmUserLocking({{ $user->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                        <x-icon name="lock-closed" class="h-4 w-4 text-red-700" />
                    </a>
                </div>
            @else
                <div x-data="{ tooltip: 'Activer' }">
                    <a href='#' wire:click="confirmUserUnlocking({{ $user->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                        <x-icon name="lock-open" class="h-4 w-4 mb-2 text-black" />
                    </a>
                </div>
            @endif
            <div x-data="{ tooltip: 'Supprimer' }">
                <a href='#' wire:click="confirmUserDeleting({{ $user->id }})" wire:loading.attr="disabled" x-tooltip="tooltip">
                    <x-icon name="trash" class="h-4 w-4 text-red-900" />
                </a>
            </div>
        @endif
    @endhasrole
</div>
