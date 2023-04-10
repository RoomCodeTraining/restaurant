<div>
    <x-form-card submit="saveUser">
        <x-slot name="form">
            {{ $this->form }}
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn">
                    Retour
                </button>
                <button class="btn btn-sm btn-primary" wire:target="saveUser" type="submit" wire:loading.attr="disabled"
                    wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
