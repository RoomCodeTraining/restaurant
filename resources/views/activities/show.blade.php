<x-app-layout>
    <x-section-header title="Details activités">
        <x-slot name="actions">
            <a href="{{ route('activities-log') }}" class="btn btn-sm btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block font-bold w-4 h-4 mt-1 mr-1 stroke-current"
                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24">
                    <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6l6 6l1.41-1.41L5.83 13H21V7z" fill="currentColor">
                    </path>
                </svg>
                Retour
            </a>
        </x-slot>
    </x-section-header>
    <div class="bg-white px-6 py-4 rounded-md shadow-lg">
        <x-action-section>
            <x-slot name="title">
                Informations de l'utilisateur
            </x-slot>

            <x-slot name="description">
                Les informations de cet utilisateur tels que son nom, son adresse.
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-8 gap-6">
                    <div class="col-span-8">
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                            <div class="w-full">
                                <dt class="text-sm font-medium text-gray-500">Photo</dt>
                                <img src="{{ $user->profile_photo_url }}" class="rounded"
                                    alt="{{ $user->username }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-span-8">
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                            <div class="w-full md:w-1/2">
                                <dt class="text-sm font-medium text-gray-500">Matricule/Identifiant</dt>
                                <dd class="text-sm font-normal text-gray-900">
                                    {{ $user->identifier }}
                                </dd>
                            </div>
                            <div class="w-full md:w-1/2">
                                <dt class="text-sm font-medium text-gray-500">Nom et prénoms</dt>
                                <dd class="text-sm font-normal text-gray-900">{{ $user->full_name }}</dd>
                            </div>
                        </div>
                    </div>
           
                </div>
            </x-slot>
        </x-action-section>
    </div>
</x-app-layout>
