<x-app-layout>
    <x-section-header title="Departements">
        <x-slot name="actions">
            <a href="{{ route('departments.create') }}" class="btn btn-secondary">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>

    <livewire:departments.departments-table />
</x-app-layout>
