<div>
    <x-form-card submit="saveUser">
        <x-slot name="form">
            <div x-data="{ user_type_id: @entangle('state.user_type_id') }"
                class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Quota petit dejeuner</span>
                    </label>
                    <input class="input input-bordered" type="text"
                        wire:model.defer="state.identifier">
                    @error('state.identifier')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                <div class="col-span-8 md:col-span-4 form-control">
                  <label class="label">
                      <span class="label-text">Quota dejeuner</span>
                  </label>
                  <input class="input input-bordered" type="text"
                      wire:model.defer="state.identifier">
                  @error('state.identifier')
                      <label class="label">
                          <span class="label-text-alt text-red-600">{{ $message }}</span>
                      </label>
                  @enderror
              </div>


            </div>
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
