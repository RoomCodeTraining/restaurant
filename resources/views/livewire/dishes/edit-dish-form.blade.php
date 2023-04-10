<div>
  <x-form-card submit="saveDish">

    <x-slot name="form">
      {{ $this->form }}
    </x-slot>
    <x-slot name="actions">
        <div class="flex items-center space-x-2">
            <button class="md:hidden btn-sm btn">
                Retour
            </button>
            <button class="btn btn-sm btn-primary" wire:target="saveDish" type="submit" wire:loading.attr="disabled"
                wire:loading.class="loading">
                Enregistrer
            </button>
        </div>
    </x-slot>
</x-form-card>
</div>
