<x-app-layout>
    <div class="space-y-4 lg:space-y-8">
        <!-- Card: User Profile -->
        <div class="flex flex-col rounded shadow-sm bg-white overflow-hidden">
            <!-- Card Header: User Profile -->
            <div class="py-4 px-5 lg:px-6 w-full bg-grey-300">
                <h3 class="flex items-center space-x-2">
                    <svg class="hi-solid hi-user-circle inline-block w-5 h-5 text-primary-500" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Profil utilisateur</span>
                </h3>
            </div>
            <!-- END Card Header: User Profile -->

            <!-- Card Body: User Profile -->
            <div class="p-5 lg:p-6 flex-grow w-full md:flex md:space-x-5">
                <p class="md:flex-none md:w-1/3 text-gray-500 text-sm mb-5">
                    Les informations de votre profil.
                </p>
                <form onsubmit="return false;" class="space-y-6 md:w-1/2">
                    <div class="space-y-1">
                        <label class="font-medium">Photo</label>
                        <div class="flex items-center space-x-4">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-300">

                                <img src="{{ $loggedInUser->profile_photo_url }}"
                                    alt="{{ $loggedInUser->full_name }}"
                                    class="rounded-full h-full w-full object-cover">

                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-8 gap-2 md:gap-6">
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Matricule</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $loggedInUser->identifier }}</dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Nom et prénoms</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $loggedInUser->full_name }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8">
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
                                        {{ optional($loggedInUser->roles()->first())->name ?? 'Utilisateur' }}</dd>
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
                                    <dt class="text-sm font-medium text-gray-500">Catégorie socio-professionnelle
                                    </dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ optional($loggedInUser->employeeStatus)->name ?? 'Aucun' }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Type de collaborateur</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ $loggedInUser->is_external ? 'Collobarateur Externe' : 'Collobarateur Ciprel' }}
                                    </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Etat du compte
                                    </dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                    <dd class="badge badge-primary badge-outline">
                                        {{ $loggedInUser->is_active ? 'Actif' : 'Inactif' }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Quota petit déjeuner</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        0
                                    </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Quota déjeuner</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        0
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Mode de paiement</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ $loggedInUser->accessCards->first()->paymentMethod->name ?? 'Aucun' }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Card Body: User Profile -->
        </div>
        <!-- END Card: User Profile -->

        <!-- Card: Change Password -->
        <div class="flex flex-col rounded shadow-sm bg-white overflow-hidden">
            <!-- Card Header: Change Password -->
            <div class="py-4 px-5 lg:px-6 w-full bg-grey-300">
                <h3 class="flex items-center space-x-2">
                    <svg class="hi-solid hi-lock-open inline-block w-5 h-5 text-primary-500" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" />
                    </svg>
                    <span>Changement du mot de passe</span>
                </h3>
            </div>
            <!-- END Card Header: Change Password -->

            <!-- Card Body: Change Password -->
            <div class="p-5 lg:p-6 flex-grow w-full md:flex md:space-x-5">
                <p class="md:flex-none md:w-1/3 text-gray-500 text-sm mb-5">
                    Changer votre mot de passe de connexion est un moyen simple de sécuriser votre compte.
                </p>
                <livewire:account.update-password-form />
            </div>
            <!-- END Card Body: Change Password -->
        </div>
        <!-- END Card: Change Password -->
    </div>
</x-app-layout>
