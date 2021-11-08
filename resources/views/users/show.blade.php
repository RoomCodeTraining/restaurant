<x-app-layout>
    <section>
        <x-section-header title="Détails">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
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
                                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $user->full_name }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $user->email }}</dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Société</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ optional($user->organization)->name ?? 'Ciprel' }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Département</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ optional($user->department)->name ?? 'Aucun' }}</dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Catégorie socio-professionnelle</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ optional($user->employeeStatus)->name ?? 'Aucun' }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Collaborateur externe</dt>
                                        <dd class="text-sm font-normal text-gray-900">
                                        <div class="badge badge-primary badge-outline">{{ $user->is_external ? 'Oui' : 'Non' }}</div>
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
