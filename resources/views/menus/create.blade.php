<x-app-layout>
    <section>
        <x-section-header title="Créer un menu">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:menus.create-menu-form>
    </section>
</x-app-layout>