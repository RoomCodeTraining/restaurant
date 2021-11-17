<div class="flex items-center space-x-2">
    <a href="{{ route('userTypes.edit', $userType) }}" title="Editer">
        <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
    </a>
    @if ($userType->users_count == 0)
        <button wire:click="confirmUserTypeDeletion({{ $userType->id }})" wire:loading.attr="disabled"
            title="Supprimer">
            <x-icon name="trash" class="h-4 w-4 text-red-700" />
        </button>
    @endif
</div>
