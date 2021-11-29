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
                @if (!$user->accessCard)
                    <div class="alert alert-error">
                        <div class="flex-1">
                            <svg viewBox="0 0 20 20" class="h-6 w-6 fill-current">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <label>Ce utilisateur ne dispose pas de carte RFID associé à son compte.</label>
                        </div>
                    </div>
                @endif
            </div>
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
            <button class="btn btn-sm btn-primary" type="submit" wire:loading.class="opacity-25"
                wire:loading.attr="disabled" wire:loading.class="loading">
                Enregistrer
            </button>
        </x-slot>
    </x-form-section>
</div>
