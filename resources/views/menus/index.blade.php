<x-app-layout>
    <section>
        <x-section-header title="Menus">
            <x-slot name="actions">
                <a href="{{ route('menus.create') }}" class="btn btn-secondary">
                    Nouveau
                </a>
            </x-slot>
        </x-section-header>
        <livewire:menus.menus-table />
    </section>
</x-app-layout>
