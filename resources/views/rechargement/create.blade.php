<x-app-layout>
    <section>
        <x-section-header title="Faire un rechargement">
            <x-slot name="actions">
                <a href="{{ route('rechargement.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:rechargement.create-rechargement-form>
    </section>

</x-app-layout>
