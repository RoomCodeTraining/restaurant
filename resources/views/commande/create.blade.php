<x-app-layout>
    <section>
        <x-section-header title="Créer une commande">
            <x-slot name="actions">
                <a href="{{ route('commande.index') }}" class="btn btn-secondary hidden md:flex">
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <livewire:commande.create-cde-form>
    </section>

</x-app-layout>
