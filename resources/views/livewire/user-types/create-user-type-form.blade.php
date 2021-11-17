<div>
    <x-form-card submit="saveUserType">
        <x-slot name="form">
            <div class="col-span-8 md:col-span-12 form-control">
                <label class="label">
                    <span class="label-text">Nom</span>
                </label>
                <input class="input input-bordered" type="text" wire:model.defer="state.name" />
                @error('state.name')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </x-slot>

        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn">
                    Retour
                </button>
                <button class="btn btn-sm btn-primary" wire:target="saveUserType" type="submit" wire:loading.attr="disabled"
                    wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
