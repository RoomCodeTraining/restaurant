<x-app-layout>
    <section>
        <x-section-header title="Editer le plat">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:dishes.edit-dish-form :dish="$dish">
    </section>

</x-app-layout>
