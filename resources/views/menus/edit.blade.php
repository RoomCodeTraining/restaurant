<x-app-layout>
    <section>
        <x-section-header title="Editer le menu">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>
        <livewire:menus.edit-menu-form :menu='$menu'>
    </section>
</x-app-layout>