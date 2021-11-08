<x-app-layout>
    <section>
        <x-section-header title="Editer un utilisateur">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="bg-accent-800 hidden md:flex px-6 py-2 font-semibold text-white rounded-md hover:bg-primary-800">
                    Retour
                </a>
            </x-slot>
        </x-section-header>
        <hr class="mb-4">

        <livewire:users.edit-user-form :user="$user">
    </section>

</x-app-layout>
