<x-app-layout>
    <x-section-header title="Créer departement">
        <x-slot name="actions">
            <a href="{{ route('departments.create') }}" class="btn btn-secondary">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>

    <livewire:departments.create-department-form />
</x-app-layout>
