<div>
    <x-form-card submit="saveMenu">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez l'entrée</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.starter_dish_id">
                            <option disabled="disabled" selected="selected">Choississez un rôle</option>
                            @foreach ($dishes as $id => $name)
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
                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez une catégorie</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.main_dish_id">
                            <option disabled="disabled" selected="selected">Choississez une catégorie</option>
                            @foreach ($dishes as $id => $name)
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
                            <span class="label-text">Choississez le p</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.second_dish_id">
                            <option disabled="disabled" selected="selected">Choississez un département</option>
                            @foreach ($dishes as $id => $name)
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
                <div class="col-span-12 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez un département</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.dessert_id">
                            <option disabled="disabled" selected="selected">Choississez un département</option>
                            @foreach ($dishes as $id => $name)
                                <option  value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.dessert_id')
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
                <button class="btn btn-primary" wire:target="saveUser" type="submit" wire:loading.attr="disabled"
                    wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
