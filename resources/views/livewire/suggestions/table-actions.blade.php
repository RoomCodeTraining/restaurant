@if(auth()->user()->can('manage', \App\Models\SuggestionBox::class) && $suggestion->user_id == auth()->user()->id)
  <div class="flex items-center">
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('suggestions-box.edit', $suggestion) }}" class="mr-2" x-tooltip="tooltip">
                <x-icon name="pencil" class="h4 w-4 text-indigo-700" />
            </a>
        </div>
          <div x-data="{ tooltip: 'Supprimer' }">
            <button wire:click="confirmSuggestionDeletion({{ $suggestion->id }})" wire:loading.attr="disabled"
                class="mr-2" x-tooltip="tooltip">
                <x-icon name="trash" class="h4 w-4 text-red-700" />
            </button>
        </div>
  </div>
@endif
