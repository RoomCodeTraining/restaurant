<x-app-layout>
    <section>
        <x-section-header title="Créer un utilisateur">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:users.create-user-form>
    </section>

</x-app-layout>