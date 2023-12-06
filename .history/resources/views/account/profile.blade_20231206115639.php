<x-app-layout>
    {{-- <div class="space-y-4 lg:space-y-8">
        <!-- Card: User Profile -->
        <div class="flex flex-col rounded shadow-sm bg-white overflow-hidden">
            <!-- Card Header: User Profile -->
            <div class="py-4 px-5 lg:px-6 w-full bg-secondary-700">
                <h3 class="flex items-center space-x-2">
                    <svg class="hi-solid hi-user-circle inline-block w-5 h-5 text-gray-50" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-50">Profil utilisateur</span>
                </h3>
            </div>
            <!-- END Card Header: User Profile -->

            <!-- Card Body: User Profile -->
            <div class="p-5 lg:p-6 flex-grow w-full md:flex md:space-x-5">
                <p class="md:flex-none md:w-1/3 text-gray-500 text-sm mb-5">
                    Les informations de votre profil.
                </p>
                <form onsubmit="return false;" class="space-y-6 md:w-1/2">
                    <div class="grid grid-cols-8 gap-6">
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full">
                                    <dt class="text-sm font-medium text-gray-500">Photo</dt>
                                    <img src="{{ $loggedInUser->profile_photo_url }}" class="rounded"
                                        alt="{{ $loggedInUser->username }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Matricule/Identifiant</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ $loggedInUser->identifier }}
                                    </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Noms et prénoms</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $loggedInUser->full_name }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8" id='card'>
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $loggedInUser->email }}</dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Contact</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $loggedInUser->contact }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Profil</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ $loggedInUser->role->name }}</dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Société</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ optional($loggedInUser->organization)->name ?? 'Ciprel' }}</dd>
                                </div>

                            </div>
                        </div>
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Département</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ optional($loggedInUser->department)->name }}
                                    </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Catégorie socio-professionnelle
                                    </dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ $loggedInUser->employeeStatus->name }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Type de collaborateur</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ $loggedInUser->userType->name }}
                                    </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Etat du compte
                                    </dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                    <dd class="badge {{ $loggedInUser->is_active ? 'badge-success' : 'badge-error' }}">
                                        {{ $loggedInUser->is_active ? 'Actif' : 'Non actif' }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        @if (!auth()->user()->isFromLunchroom())
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Quota petit déjeuner</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($loggedInUser->accessCard)->quota_breakfast ?? 0 }}
                                        </dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Quota déjeuner</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($loggedInUser->accessCard)->quota_lunch ?? 0 }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Numéro de la carte</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($loggedInUser->accessCard)->identifier ?? 'Non défini' }}
                                        </dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Mode de paiement</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($loggedInUser->accessCard)->paymentMethod->name ?? 'Non défini' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <!-- END Card Body: User Profile -->
        </div>
        <!-- END Card: User Profile -->
        @if (!auth()->user()->isFromLunchroom())
            <div class="flex flex-col rounded shadow-sm  overflow-hidden">
                <div class="p-5 lg:p-6 flex-grow w-full md:flex md:space-x-5">
                    <livewire:account.order-config-form />
                </div>
            </div>
        @endif
        <!-- Card: Change Password -->
        <div class="flex flex-col rounded shadow-sm overflow-hidden">
            <div class="p-5 lg:p-6 flex-grow w-full md:flex md:space-x-5">
                <livewire:account.update-password-form />
            </div>
        </div>
        <!-- END Card: Change Password -->
    </div> --}}
    <div class="container mx-auto">
        <x-section-header title='Détails sur le compte utilisateur'>
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block font-bold w-4 h-4 mr-1 stroke-current"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 12 12">
                        <g fill="none">
                            <path
                                d="M5.898 2.007L6 2a.75.75 0 0 1 .743.648l.007.102v2.499l2.5.001a.75.75 0 0 1 .743.648L10 6a.75.75 0 0 1-.648.743l-.102.007l-2.5-.001V9.25a.75.75 0 0 1-.648.743L6 10a.75.75 0 0 1-.743-.648L5.25 9.25V6.749l-2.5.001a.75.75 0 0 1-.743-.648L2 6a.75.75 0 0 1 .648-.743l.102-.007l2.5-.001V2.75a.75.75 0 0 1 .648-.743L6 2l-.102.007z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                    Retour
                </a>
            </x-slot>

        </x-section-header>
        <livewire:profil.user-profil-view :user="$user" />

        <div class="mt-8 mb-4">
            <!-- END Card: User Profile -->
            @if (!auth()->user()->isFromLunchroom())
                <div class="flex flex-col rounded shadow-sm  overflow-hidden">
                    <div class="p-5 lg:p-6 flex-grow w-full md:flex md:space-x-5">

                    </div>
                </div>
                <livewire:account.order-config-form />
            @endif
            <!-- Card: Change Password -->
            <div class="flex flex-col rounded shadow-sm overflow-hidden">
                <div class="p-5 lg:p-6 flex-grow w-full md:flex md:space-x-5">
                    <livewire:account.update-password-form />
                </div>
            </div>
            <!-- END Card: Change Password -->

        </div>
    </div>
</x-app-layout>
