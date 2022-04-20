<div>
    <x-form-card submit="saveQuota">
        <x-slot name="form">
            @if (!$showReloadButton)
                <div class='alert alert-info mb-2 text-center'>
                    Les informations de facturation de cet utilisateur ne peuvent etre modifiées car ses quota ne
                    sont pas epuisés.
                </div>
            @endif
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Quota petit dejeuner</span>
                    </label>
                    <input class="input input-bordered" {{ $accessCard->quota_breakfast > 0 ? 'disabled' : '' }} type="number"
                        wire:model.defer="quota_breakfast">
                    @error('quota_breakfast')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Quota dejeuner</span>
                    </label>
                    <input class="input input-bordered" {{ $accessCard->quota_lunch > 0 ? 'disabled' : '' }} type="number"
                        wire:model.defer="quota_lunch">
                    @error('quota_lunch')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                <div class="col-span-8 md:col-span-8 form-control">
                  <label class="label">
                    <span class="label-text">Mode de paiement</span>
                </label>
                <select {{ $accessCard->quota_breakfast != 0 || $accessCard->quota_lunch != 0 ? 'disabled' : '' }}
                    class="select select-bordered w-full" wire:model.defer="payment_method_id">
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
            </div>
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn">
                    Retour
                </button>
                @if ($showReloadButton)
                    <button class="btn btn-sm btn-primary" wire:target="saveQuota" type="submit"
                        wire:loading.attr="disabled" wire:loading.class="loading">
                        Enregistrer
                    </button>
                @endif
            </div>
        </x-slot>
    </x-form-card>
</div>
