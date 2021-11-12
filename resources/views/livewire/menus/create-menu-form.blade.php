<div>
    <x-form-card submit="saveMenu">
        <x-slot name="form">
            <div class="col-span-8 md:col-span-12 form-control">
                <label class="label">
                    <span class="label-text">Date</span>
                </label>
                <input class="input input-bordered" type="date" wire:model.defer="state.served_at" />
                @error('state.served_at')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez l'entrée</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.starter_dish_id">
                            <option selected="selected">Veuillez choisir</option>
                            @foreach ($starter_dishes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.starter_dish_id')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez un dessert</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.dessert_id">
                            <option selected="selected">Veuillez choisir</option>
                            @foreach ($dessert_dishes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.dessert_id')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez le plat 1</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.main_dish_id">
                            <option selected="selected">Veuillez choisir</option>
                            @foreach ($main_dishes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.main_dish_id')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez le plat 2</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.second_dish_id">
                            <option selected="selected">Veuillez choisir</option>
                            @foreach ($main_dishes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.second_dish_id')
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
                <button class="btn-sm btn-primary" wire:target="saveUser" type="submit" wire:loading.attr="disabled"
                    wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
