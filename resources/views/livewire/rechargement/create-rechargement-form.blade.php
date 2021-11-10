<div>
      <x-form-card submit="saveDish">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Matricule</span>
                    </label>
                    <input class="input input-bordered" type="text">
                    @error('state.matricule')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                   <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Nom et prénoms</span>
                        </label>
                        <input class="input input-bordered" type="text">
                        @error('state.name')
                            <label class="label">
                                <span class="label-text-alt text-red-600">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Numéro de la carte</span>
                    </label>
                    <input class="input input-bordered" type="text">
                    @error('state.num')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez un type de plat</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.dish_type_id">
                            <option selected="selected">Veuillez choisir</option>

                                <option value=""> Riz + sauce gouagouassou</option>

                        </select>
                    </div>
                </div>
                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Quota petit déjeuner</span>
                        </label>
                        <select class="select select-bordered w-full">
                            <option selected="selected">Veuillez choisir un quota</option>

                                <option value=""></option>

                        </select>
                    </div>
                </div>
                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Quota déjeuner</span>
                        </label>
                        <select class="select select-bordered w-full">
                            <option selected="selected">Veuillez choisir un quota</option>

                                <option value=""></option>

                        </select>
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn">
                    Retour
                </button>
                <button class="btn btn-primary" type="submit">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>

</div>
