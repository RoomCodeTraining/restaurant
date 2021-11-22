<div>
    <x-form-section submit="topUp">
        <x-slot name="title">
            Rechargement et facturation
        </x-slot>

        <x-slot name="description">
            Les paramètres concernant le rechargement et le mode de paiement.
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6 sm:col-span-8 form-control">
                <label class="label">
                    <span class="label-text">Mode de paiement</span>
                </label>
                <select class="select select-bordered w-full" wire:model.defer="state.payment_method_id">
                    @foreach ($paymentMethods as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('state.payment_method_id')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="col-span-6 sm:col-span-8 form-control">
                <label class="label">
                    <span class="label-text">Quota petit-dejeuner</span>
                </label>
                <input class="input input-bordered" type="text" wire:model.defer="state.quota_breakfast">
                @error('state.quota_breakfast')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="col-span-6 sm:col-span-8 form-control">
                <label class="label">
                    <span class="label-text">Quota dejeuner</span>
                </label>
                <input class="input input-bordered" type="text" wire:model.defer="state.quota_lunch">
                @error('state.quota_lunch')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="topUpSuccess">
                Enregistré.
            </x-action-message>
            <button class="btn btn-sm btn-primary" type="submit" wire:loading.class="opacity-25"
                wire:loading.attr="disabled" wire:loading.class="loading">
                Enregistrer
            </button>
        </x-slot>
    </x-form-section>
</div>
