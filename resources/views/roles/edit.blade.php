<x-app-layout>
    <section>
        <x-section-header title="Editer un rôle">
            <x-slot name="actions">
                <a href="{{ route('roles.index') }}" class="bg-accent-800 hidden md:flex px-6 py-2 font-semibold text-white rounded-md hover:bg-primary-800">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:roles.edit-role-form :role="$role">
    </section>

</x-app-layout>
