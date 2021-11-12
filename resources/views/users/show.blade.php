<x-app-layout>
    <section>
        <x-section-header title="Détails">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn-sm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block font-bold w-4 h-4 mt-1 mr-1 stroke-current" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"><path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6l6 6l1.41-1.41L5.83 13H21V7z" fill="currentColor"></path></svg>
                    Retour
                </a>
            </x-slot>
        </x-section-header>

        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="mt-5 md:mt-0 md:col-span-3">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white">
                        <div class="grid grid-cols-8 gap-2 md:gap-6">
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Matricule</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $user->identifier }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Nom et prénoms</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $user->full_name }}</dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $user->email }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Contact</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $user->contact }}</dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Profil</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($user->roles()->first())->name ?? 'Utilisateur' }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Société</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($user->organization)->name ?? 'Ciprel' }}</dd>
                                    </div>

                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Département</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($user->department)->name }}
                                        </dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Catégorie socio-professionnelle
                                        </dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ optional($user->employeeStatus)->name ?? 'Aucun' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Type de collaborateur</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                            {{ $user->is_external ? 'Collobarateur Externe' : 'Collobarateur Ciprel' }}
                                        </dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Etat du compte
                                        </dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                        <dd class="badge badge-primary badge-outline">
                                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
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
                                            {{ $user->accessCards->first()->paymentMethod->name ?? 'Aucun' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
