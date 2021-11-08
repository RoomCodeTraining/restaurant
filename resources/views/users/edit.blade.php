<x-app-layout>
    <section>
        <x-section-header title="Editer un utilisateur">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:users.edit-user-form :user="$user">
    </section>

</x-app-layout>
