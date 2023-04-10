<div>
    <x-form-card submit="saveOrganization">
        <x-slot name="form">
            {{ $this->form }}
        </x-slot>

        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn btn-sm">
                    Retour
                </button>
                <button class="btn btn-sm btn-primary" wire:target="saveOrganization" type="submit"
                    wire:loading.attr="disabled" wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
