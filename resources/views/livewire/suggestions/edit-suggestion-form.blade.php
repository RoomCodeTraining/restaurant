<div>
  <x-form-card submit="saveSuggestion">

    <x-slot name="form">
        <div class="grid grid-cols-8 gap-2 md:gap-4">
            <div class="col-span-8 md:col-span-12 form-control">
                <label class="label">
                    <span class="label-text">Votre suggestion</span>
                </label>
                <textarea class="input input-bordered" type="text" wire:model.defer="suggestionContent"></textarea>
                @error('suggestionContent')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </div>
    </x-slot>
    <x-slot name="actions">
        <div class="flex items-center space-x-2">
            <button class="md:hidden btn-sm btn">
                Retour
            </button>
            <button class="btn btn-sm btn-primary" wire:target="saveSuggestion" type="submit" wire:loading.attr="disabled"
                wire:loading.class="loading">
                Enregistrer
            </button>
        </div>
    </x-slot>
</x-form-card>
</div>
