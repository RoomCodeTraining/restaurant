<div class="flex items-center space-x-2">
    <div class="flex flex-col" x-data="{ tooltip: 'Voir les utilisateurs' }">
        <button wire:click="showUsers({{ $row }})" x-tooltip="tooltip">
            <x-icon name="eye" class="h-4 w-4 text-accent-800" />
        </button>
    </div>
</div>
