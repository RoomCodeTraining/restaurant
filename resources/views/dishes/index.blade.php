<x-app-layout>
    <x-section-header title="Plats">
        <x-slot name="actions">
            <a href="{{ route('dishes.create') }}" class="btn btn-secondary">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>

    <livewire:dishes.dishes-table />
</x-app-layout>
