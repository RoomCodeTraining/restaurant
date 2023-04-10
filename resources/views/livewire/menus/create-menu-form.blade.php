<div>
    <x-form-card submit="saveMenu">
         <x-slot name="form" class='space-y-12'>
         {{ $this->form }}
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn-sm">
                    Retour
                </button>
            <button class="btn btn-sm btn-primary" wire:target="saveMenu" type="submit" wire:loading.attr="disabled"
                wire:loading.class="loading">
                Enregistrer
            </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
