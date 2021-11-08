<x-app-layout>
    <x-section-header title="Utilisateurs">
        <x-slot name="actions">
            <a href="{{ route('users.create') }}" class="bg-accent-800 hidden md:flex px-6 py-2 font-semibold text-white rounded-md hover:bg-primary-800">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>
    <hr class="mb-4">

    <livewire:users.users-table />
</x-app-layout>
