<x-app-layout>
    <section>
        <x-section-header title="Créer une suggestion">
            <x-slot name="actions">
                <a href="{{ route('suggestions-box.index') }}" class="btn btn-sm btn-secondary hidden md:flex">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="inline-block font-bold w-4 h-4 mt-1 mr-1 stroke-current"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24">
                        <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6l6 6l1.41-1.41L5.83 13H21V7z" fill="currentColor">
                        </path>
                    </svg>
                    Retour
                </a>
            </x-slot>
        </x-section-header>
        <div class='alert alert-info mb-2'>
          Votre suggestion nous permettrons d'améliorer l'application ou le service de la cantine.
        </div>
        <livewire:suggestions.create-suggestion-form />
    </section>
</x-app-layout>