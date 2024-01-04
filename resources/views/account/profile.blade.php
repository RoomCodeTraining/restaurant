<x-app-layout>
    <div class="space-y-4 lg:space-y-8">
        <x-section-header title='Détails sur le compte utilisateur'/>
        <livewire:profil.user-profil-view :user="$user" />
        <!-- Card: Change Password -->
        <livewire:account.update-password-form />
    </div>

</x-app-layout>
