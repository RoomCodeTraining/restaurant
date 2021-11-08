<div>
    <x-form-card submit="saveUser">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Matricule</span>
                    </label>
                    <input placeholder="Entrez le matricule" class="flex-1 w-full rounded-md mx-2 border-gray-300  focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none" type="text" wire:model.defer="state.identifier">
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
                    <input placeholder="Entrez le nom" class="flex-1 w-full rounded-md mx-2 py-2 border-gray-300 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none" type="text" wire:model.defer="state.last_name">
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
                    <input placeholder="Entrez le prénoms" class="flex-1 w-full rounded-md mx-2 py-2 border-gray-300 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none" type="text" wire:model.defer="state.first_name">
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
                    <input placeholder="Entrez l'E-mail" class="flex-1 w-full rounded-md mx-2 py-2 border-gray-300 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none" type="text" wire:model.defer="state.email">
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
                    <input placeholder="Entrez l'username" class="flex-1 w-full rounded-md mx-2 py-2 border-gray-300 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none" type="text" wire:model.defer="state.username">
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
                    <input placeholder="Entrez le contact" class="flex-1 w-full rounded-md mx-2 py-2 border-gray-300 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none" type="text" wire:model.defer="state.contact">
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
                        <select class="flex-1 w-full py-2 border-gray-300 focus:border-green-400 text-gray-600 rounded-md placeholder-gray-400 outline-none" wire:model.defer="role">
                            <option selected="selected">Veuillez choisir un rôle</option>
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
                            <span class="label-text">Catégorie professionnelle</span>
                        </label>
                        <select class="flex-1 w-full border-gray-300 focus:border-green-400 text-gray-600 rounded-md placeholder-gray-400 outline-none" wire:model.defer="state.employee_status_id">
                            <option selected="selected">Veuillez choisir une catégorie</option>
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
                            <span class="label-text">Département</span>
                        </label>
                        <select class="flex-1 w-full py-2 border-gray-300 focus:border-green-400 text-gray-600 rounded-md placeholder-gray-400 outline-none" wire:model.defer="state.department_id">
                            <option selected="selected">Veuillez choisir un département</option>
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
                            <span class="label-text">Société</span>
                        </label>
                        <select class="flex-1 w-full py-2 border-gray-300 text-gray-600 rounded-md placeholder-gray-400 outline-none" wire:model.defer="state.organization_id">
                            <option selected="selected">Veuillez choisir une société</option>
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
                <button class="py-3 px-8 bg-red-700 hover:bg-opacity-50 text-white font-bold">
                    Annuler
                </button>
                <button class="py-3 px-8 bg-secondary-800 hover:bg-opacity-50 text-white font-bold" wire:target="saveUser" type="submit" wire:loading.attr="disabled" wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>

    </x-form-card>
</div>
