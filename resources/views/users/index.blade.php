<x-app-layout>
    <x-section-header title="Utilisateurs">
        <x-slot name="actions">
            <a href="{{ route('users.create') }}" class="btn btn-secondary">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>

    <livewire:users.users-table />
</x-app-layout>
