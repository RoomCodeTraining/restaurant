<x-app-layout>
    <x-section-header title="Rôles">
        <x-slot name="actions">
            <a href="{{ route('users.create') }}" class="btn btn-secondary">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>

    <livewire:roles.roles-table />
</x-app-layout>
