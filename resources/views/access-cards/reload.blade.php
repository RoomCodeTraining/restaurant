<x-app-layout>
  <x-section-header title="Recharger de la carte de Mr/Mme {{ $accessCard->user->full_name }}">
    <x-slot name="actions">
      <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary hidden md:flex">
          <svg xmlns="http://www.w3.org/2000/svg" class="inline-block font-bold w-4 h-4 mt-1 mr-1 stroke-current" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"><path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6l6 6l1.41-1.41L5.83 13H21V7z" fill="currentColor"></path></svg>
          Retour
      </a>
  </x-slot>
  </x-section-header>
  <div class="bg-white px-6 py-4 rounded-md shadow-lg">
    <livewire:access-cards.reload-card-form :accessCard="$accessCard" />
  </div>
</x-app-layout>
