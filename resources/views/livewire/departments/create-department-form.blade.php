<div>
    <x-form-card submit="saveDepartment">
        <x-slot name="form">
            <div class="grid grid-cols-8">
                <div class="col-span-8 md:col-span-12">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Nom</span>
                        </label>
                        <input class="input input-bordered" type="text" name="state.name"
                            wire:model.defer="state.name" />
                        @error('state.name')
                            <label class="label">
                                <span class="label-text-alt text-red-600">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn btn-sm">
                    Retour
                </button>
                <button class="btn btn-sm btn-primary" wire:target="saveDepartment" type="submit"
                    wire:loading.attr="disabled" wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
