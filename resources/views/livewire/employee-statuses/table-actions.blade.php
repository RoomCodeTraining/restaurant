<div class="flex items-center space-x-2">
    <div x-data="{ tooltip: 'Modifier' }">
        <a href="{{ route('employeeStatuses.edit', $model) }}" x-tooltip="tooltip">
            <x-icon name="pencil" class="h-4 w-4 text-accent-800" />
        </a>
    </div>
    @if ($model->users_count == 0)
        <div x-data="{ tooltip: 'Supprimer' }">
            <button wire:click="confirmModelDeletion({{ $model->id }})" wire:loading.attr="disabled"
                x-tooltip="tooltip">
                <x-icon name="trash" class="h-4 w-4 text-red-700" />
            </button>
        </div>
    @endif
</div>
