<section>
    <x-form-card submit="saveRole">
        <x-slot name="form">
            <fieldset class="mt-4 mb-2 p-5 border border-gray-200">
                <legend class="text-base text-gray-900 font-semibold">
                    Informations du rôle
                </legend>

                <div class="grid grid-cols-1 gap-4">
                    <div class="col-span-1 form-control">
                        <label class="label">
                            <span class="label-text">Désignation</span>
                        </label>
                        <input class="input input-bordered" type="text" wire:model.defer="state.name">
                        @error('state.name')
                            <label class="label">
                                <span class="label-text-alt text-red-600">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="col-span-1 form-control">
                        <label class="label">
                            <span class="label-text">Description</span>
                        </label>
                        <textarea class="textarea h-24 textarea-bordered"
                            wire:model.defer="state.description"></textarea>
                        @error('state.description')
                            <label class="label">
                                <span class="label-text-alt text-red-600">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="mt-4 mb-2 p-5 border border-gray-200">
                <legend class="text-base text-gray-900 font-semibold">
                    Permissions à accordées à ce rôle
                </legend>
                @foreach ($permissions->chunk(2) as $chunkedPermissions)
                    <div class="grid grid-cols-2 gap-x-5 mb-2">
                        @foreach ($chunkedPermissions as $permission)
                            <ul class="permission-tree m-0 p-0 list-unstyled col-span-1">
                                <li>
                                    <input type="checkbox" class="checkbox checkbox-sm indeterminate-checkbox"
                                        wire:model.defer="rolePermissions" id="{{ $permission->id }}"
                                        value="{{ $permission->id }}"
                                        {{ in_array($permission->id, $rolePermissions ?? [], true) ? 'checked' : '' }}>
                                    <label for="{{ $permission->id }}">
                                        {{ $permission->description ?? $permission->name }}
                                    </label>
                                    @if ($permission->children->count())
                                        <ul class="list-unstyled ml-4">
                                            @foreach ($permission->children as $chilPermission)
                                                <li>
                                                    <input type="checkbox"
                                                        class="checkbox checkbox-sm indeterminate-checkbox"
                                                        wire:model.defer="rolePermissions"
                                                        value="{{ $chilPermission->id }}"
                                                        id="{{ $chilPermission->id }}"
                                                        {{ in_array($chilPermission->id, $rolePermissions ?? [], true) ? 'checked' : '' }}>
                                                    <label for="{{ $chilPermission->id }}">
                                                        {{ $chilPermission->description ?? $chilPermission->name }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            </ul>
                        @endforeach
                    </div>
                @endforeach
            </fieldset>
        </x-slot>

        <x-slot name="actions">
            <button class="btn btn-sm btn-primary" wire:target="saveRole" wire:loading.attr="disabled"
                wire:loading.class="loading" type="submit">
                Enregistrer
            </button>
        </x-slot>
    </x-form-card>
</section>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/indeterminate-checkbox.js') }}"></script>
    <script>
        window.addEventListener('load', () => {
            IndeterminateCheckbox.init()
        });
    </script>
@endpush
