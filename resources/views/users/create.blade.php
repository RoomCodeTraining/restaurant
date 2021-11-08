<x-app-layout>
    <section>
        <x-section-header title="Créer un utilisateur">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="bg-accent-800 hidden md:flex px-6 py-2 font-semibold text-white rounded-md hover:bg-primary-800">
                    Retour
                </a>
            </x-slot>
        </x-section-header>
        <hr class="mb-4">

        <livewire:users.create-user-form>
    </section>

</x-app-layout>
