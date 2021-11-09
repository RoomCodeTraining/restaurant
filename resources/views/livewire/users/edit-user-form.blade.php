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
                        <span class="label-text">Photo</span>
                    </label>
                    <input class="input input-bordered" type="file" wire:model.defer="profile_photo">
                    @error('profile_photo')
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
                            <span class="label-text">Profil</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="role">
                            <option selected="selected">Veuillez choisir</option>
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
                            <span class="label-text">Type de Collaborateur</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.is_external">
                            <option selected="selected">Veuillez choisir</option>
                            <option value="no">Collobarateur Ciprel</option>
                            <option value="yes">Collobarateur externe</option>
                        </select>
                    </div>
                    @error('state.is_external')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Département</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.department_id">
                            <option selected="selected">Veuillez choisir</option>
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
                            <span class="label-text">Catégorie professionnelle</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.employee_status_id">
                            <option selected="selected">Veuillez choisir</option>
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
