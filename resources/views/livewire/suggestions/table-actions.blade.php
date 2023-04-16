@if(auth()->user()->can('manage', \App\Models\SuggestionBox::class) && $suggestion->user_id == auth()->user()->id)
  <div class="flex items-center space-x-2">
        <div x-data="{ tooltip: 'Modifier' }">
            <a href="{{ route('suggestions-box.edit', $suggestion) }}" x-tooltip="tooltip">
                <x-icon name="pencil" class="h-4 w-4 text-indigo-700" />
            </a>
        </div>
          <div x-data="{ tooltip: 'Supprimer' }">
            <a href='#' wire:click="confirmSuggestionDeletion({{ $suggestion->id }})" wire:loading.attr="disabled"
                class="" x-tooltip="tooltip">
                <x-icon name="trash" class="h-4 w-4 text-red-700" />
            </a>
        </div>
  </div>
@endif
