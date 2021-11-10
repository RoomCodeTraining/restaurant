<x-app-layout>
    <x-section-header title="Créer société">
        <x-slot name="actions">
            <a href="{{ route('organizations.index') }}" class="btn btn-secondary">
                Retour
            </a>
        </x-slot>
    </x-section-header>

    <livewire:organizations.create-organization-form />
</x-app-layout>
