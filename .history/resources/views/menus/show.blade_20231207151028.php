<x-app-layout>
    <section>
        <x-section-header title="Détails du menu du {{ $menu->served_at->format('d/m/Y') }}">
            <x-slot name="actions">
                <a href="{{ route('menus.index') }}" class="btn btn-sm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="inline-block font-bold w-4 h-4 mt-1 mr-1 stroke-current"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24">
                        <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6l6 6l1.41-1.41L5.83 13H21V7z" fill="currentColor"></path>
                    </svg>
                    Retour
                </a>
            </x-slot>
        </x-section-header>


        <livewire.tables.view-infolist.menu-infolist :menu='$menu'>
    </section>
</x-app-layout>
