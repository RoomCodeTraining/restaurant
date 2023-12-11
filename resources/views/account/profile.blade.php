<x-app-layout>
    <div class="space-y-4 lg:space-y-8">
        <x-section-header title='Détails sur le compte utilisateur'>
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="inline-block font-bold w-4 h-4 mt-1 mr-1 stroke-current"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24">
                        <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6l6 6l1.41-1.41L5.83 13H21V7z" fill="currentColor"></path>
                    </svg>
                    Retour
                </a>
            </x-slot>
        </x-section-header>
        <livewire:profil.user-profil-view :user="$user" />
        <!-- END Card: User Profile -->
        @if (!auth()->user()->isFromLunchroom())
            <livewire:account.order-config-form />
        @endif
        <!-- Card: Change Password -->
        <livewire:account.update-password-form />
    </div>

</x-app-layout>
