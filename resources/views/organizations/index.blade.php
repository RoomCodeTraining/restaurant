<x-app-layout>
    <x-section-header title="Sociétés">
        <x-slot name="actions">
            <a href="{{ route('organizations.create') }}" class="btn btn-secondary">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>

    <livewire:organizations.organizations-table />
</x-app-layout>
