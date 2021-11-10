<x-app-layout>
    <x-section-header title="Commande">
        <x-slot name="actions">
            <a href="{{ route('commande.create') }}" class="btn btn-secondary">
                Nouveau
            </a>
        </x-slot>
    </x-section-header>


</x-app-layout>
