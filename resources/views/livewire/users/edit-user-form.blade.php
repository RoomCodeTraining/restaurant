<div>
    <x-form-card submit="saveUser">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Matricule</span>
                    </label>
                    <input class="input input-bordered" type="text" wire:model.defer="state.identifier">
                    @error('state.identifier')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Nom</span>
                    </label>
                    <input class="input input-bordered" type="text" wire:model.defer="state.last_name">
                    @error('state.last_name')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Prénoms</span>
                    </label>
                    <input class="input input-bordered" type="text" wire:model.defer="state.first_name">
                    @error('state.first_name')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">E-mail</span>
                    </label>
                    <input class="input input-bordered" type="text" wire:model.defer="state.email">
                    @error('state.email')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Username</span>
                    </label>
                    <input class="input input-bordered" type="text" wire:model.defer="state.username">
                    @error('state.username')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Contact</span>
                    </label>
                    <input class="input input-bordered" type="text" wire:model.defer="state.contact">
                    @error('state.contact')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez un rôle</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="role">
                            <option disabled="disabled" selected="selected">Choississez un rôle</option>
                            @foreach ($roles as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('role')
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
                        <select class="select select-bordered w-full" wire:model.defer="state.employee_status_id">
                            <option disabled="disabled" selected="selected">Choississez une catégorie</option>
                            @foreach ($employeeStatuses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.employee_status_id')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez un département</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.department_id">
                            <option disabled="disabled" selected="selected">Choississez un département</option>
                            @foreach ($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.department_id')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez une société</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.organization_id">
                            <option disabled="disabled" selected="selected">Choississez une société</option>
                            @foreach ($organizations as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.organization_id')
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
                <button class="btn btn-primary" wire:target="saveUser" type="submit" wire:loading.attr="disabled" wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
