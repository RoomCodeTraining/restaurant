<x-app-layout>
    <section>
        <x-section-header title="Editer un rôle">
            <x-slot name="actions">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:roles.edit-role-form :role="$role">
    </section>

</x-app-layout>
