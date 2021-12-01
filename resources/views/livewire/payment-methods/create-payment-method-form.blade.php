<div>
    <x-form-card submit="savePaymentMethod">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-8 form-control">
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

                    <div class="col-span-8 md:col-span-8 form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea  class="input input-bordered h-16" type="text" wire:model.defer="state.description"></textarea>
                    @error('state.description')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

            </div>
        </x-slot>

        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn-sm">
                    Retour
                </button>
                <button class="btn btn-sm btn-primary" wire:target="savePaymentMethod" type="submit" wire:loading.attr="disabled"
                    wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
