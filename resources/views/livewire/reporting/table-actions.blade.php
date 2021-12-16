<div class="flex items-center space-x-2">
    <div class="flex flex-col" x-data="{ tooltip: 'Voir le détails' }">
        <button wire:click="showDetails({{ $row }})" x-tooltip="tooltip">
            <x-icon name="eye" class="h-4 w-4 text-secondary-700" />
        </button>
    </div>
</div>
