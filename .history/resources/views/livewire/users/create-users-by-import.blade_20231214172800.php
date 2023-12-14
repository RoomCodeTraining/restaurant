<div>
    <button @click="$wire.$toggle('showModal')" class="btn btn-sm btn-secondary">
        Importer
    </button>

    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            Importer depuis un fichier Excel
        </x-slot>

        <x-slot name="content">
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Choisissez le fichier des utilisateurs</span>
                </label>
                <input class="input input-bordered" type="file" wire:model.defer="file">
                @error('file')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="inline-flex items-center space-x-2">
                <button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                    {{ __('Annuler') }}
                </button>
                <button type="submit">
                    {{ __('Importer') }}
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
