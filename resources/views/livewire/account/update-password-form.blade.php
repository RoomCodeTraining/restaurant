<form wire:submit.prevent="updatePassword" class="space-y-6 md:w-1/2">
    <div class="space-y-1 form-control">
        <label class="label">
            <span class="label-text">Mot de passe actuel</span>
        </label>
        <input class="input input-bordered" type="password" wire:model.defer="state.current_password" autocomplete="current-password">
        @error('current_password')
            <label class="label">
                <span class="label-text-alt text-red-600">{{ $message }}</span>
            </label>
        @enderror
    </div>

    <div class="space-y-1 form-control">
        <label class="label">
            <span class="label-text">Nouveau mot de passe</span>
        </label>
        <input class="input input-bordered" type="password" wire:model.defer="state.password" autocomplete="new-password">
        @error('password')
            <label class="label">
                <span class="label-text-alt text-red-600">{{ $message }}</span>
            </label>
        @enderror
    </div>

    <div class="space-y-1 form-control">
        <label class="label">
            <span class="label-text">Entrez à nouveau le mot de passe</span>
        </label>
        <input class="input input-bordered" type="password" wire:model.defer="state.password_confirmation" autocomplete="new-password">
        @error('password_confirmation')
            <label class="label">
                <span class="label-text-alt text-red-600">{{ $message }}</span>
            </label>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">
        Changer de mot de passe
    </button>
</form>
