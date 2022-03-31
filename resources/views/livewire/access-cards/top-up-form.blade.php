<div>
    <x-form-section>
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
                            <label>Cet utilisateur ne dispose pas de carte RFID associée à son compte.</label>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-span-6 sm:col-span-8 form-control">
                @if ($user->typeAndCategoryCanUpdated())
                    <div class='alert alert-info mb-2 text-center'>
                        Les informations de facturation de cet utilisateur ne peuvent etre modifiées car ses quota ne
                        sont pas epuisés.
                    </div>
                @endif
                <label class="label">
                    <span class="label-text">Mode de paiement</span>
                </label>
                <select {{ $state['quota_breakfast'] > 0 || $state['quota_lunch'] > 0 ? 'disabled' : '' }}
                    class="select select-bordered w-full" wire:model.defer="state.payment_method_id">
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
                <input class="input input-bordered"
                    {{ optional($user->accessCard)->quota_breakfast > 0 ? 'disabled' : '' }} type="text"
                    wire:model.lazy="state.quota_breakfast">
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
                <input class="input input-bordered"
                    {{ optional($user->accessCard)->quota_lunch > 0 ? 'disabled' : '' }} type="text"
                    wire:model.lazy="state.quota_lunch">
                @error('state.quota_lunch')
                    <label class="label">
                        <span class="label-text-alt text-red-600">{{ $message }}</span>
                    </label>
                @enderror
            </div>

        </x-slot>
        @if ($user->accessCard)
            <x-slot name="actions">
                @if ($user->accessCard->quota_breakfast == 0 || $user->accessCard->quota_lunch == 0)
                    <div wire:loading.delay.longer wire:target='topUp'>
                        Rechargement en cours...
                    </div>
                    <button wire:loading.attr="disabled"
                             wire:loading.remove
                              wire:click.prevent="topUp"
                              class="btn btn-sm btn-primary"
                              type="submit"
                        wire:loading.class="opacity-25" >
                        Enregistrer
                    </button>
                @endif
            </x-slot>
        @endif
    </x-form-section>
</div>
