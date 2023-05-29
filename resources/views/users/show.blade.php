<x-app-layout>
    <section class="mb-4">
        <x-section-header title="Détails">
            <x-slot name="actions">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
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
                                    {{ $user->role->name }}</dd>
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
                                <dt class="text-sm font-medium text-gray-500">
                                    Catégorie socio-professionnelle
                                </dt>
                                <dd class="text-sm font-normal text-gray-900">
                                    {{ $user->employeeStatus->name }}
                                </dd>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-8">
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                            <div class="w-full md:w-1/2">
                                <dt class="text-sm font-medium text-gray-500">Type de collaborateur</dt>
                                <dd class="text-sm font-normal text-gray-900">
                                    {{ $user->userType->name }}
                                </dd>
                            </div>
                            <div class="w-full md:w-1/2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Etat du compte
                                </dt>
                                <dd class="text-sm font-normal text-gray-900">
                                <dd class="badge {{ $user->is_active ? 'badge-success' : 'badge-error' }}">
                                    {{ $user->is_active ? 'Actif' : 'Non actif' }}
                                </dd>
                            </div>
                        </div>
                    </div>
                    @if (!$user->isFromLunchroom())
                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Quota petit déjeuner</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ optional($user->accessCard)->quota_breakfast ?? 0 }}
                                    </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Quota déjeuner</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ optional($user->accessCard)->quota_lunch ?? 0 }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Numéro de la carte</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ optional($user->accessCard)->identifier ?? 'Non défini' }}
                                    </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Mode de paiement</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ optional($user->accessCard)->paymentMethod->name ?? 'Non défini' }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-8">
                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">A droit au petit dejeuner ?</dt>
                                        <dd class="badge {{ $user->is_entitled_breakfast ? 'badge-success' : 'badge-error' }}">
                                            {{ $user->is_entitled_breakfast ? 'Oui' : 'Non' }}
                                        </dd>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Peut commander deux plats par jour ?</dt>
                                        <dd class="badge {{ $user->can_order_two_dishes ? 'badge-success' : 'badge-error' }}">
                                            {{ $user->can_order_two_dishes ? 'Oui' : 'Non' }}
                                        </dd>
                                </div>

                            </div>
                        </div>
                    @endif

                </div>
            </x-slot>
        </x-action-section>

        @if ($user->can('create', App\Models\Order::class) && !$user->hasRole([App\Models\Role::ADMIN_LUNCHROOM, App\Models\Role::OPERATOR_LUNCHROOM]))
            <x-section-border></x-section-border>

            <div class="md:grid md:grid-cols-3 md:gap-6">
                <x-section-title>
                    <x-slot name="title">Statistiques</x-slot>
                    <x-slot name="description">Les statistiques de cet utilisateur.</x-slot>
                </x-section-title>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-2">
                        <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                            <div
                                class="p-3 mr-4 text-primary-500 bg-primary-100 rounded-full dark:text-primary-100 dark:bg-primary-500">
                                <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24">
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path
                                        d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.001a.996.996 0 0 1-.993.999H3.993A.996.996 0 0 1 3 20.001V10zm16 0H5v9h14v-9zM4 5v3h16V5H4zm5 7h6v2H9v-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Commandes effectuées
                                </p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    {{ $totalOrders }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                            <div
                                class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                                <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24">
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path
                                        d="M17 8h3l3 4.056V18h-2.035a3.5 3.5 0 0 1-6.93 0h-5.07a3.5 3.5 0 0 1-6.93 0H1V6a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2zm0 2v3h4v-.285L18.992 10H17z" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Commandes consommées
                                </p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    {{ $totalOrdersCompleted }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="py-4">
                        <span class="font-bold">Dernières commandes</span>
                    </div>
                    <x-table hover class="shadow bg-white" :columns="[
                        'Menu du' => fn ($order) => $order->menu->served_at->format('d/m/Y'),
                        'Plat' => 'dish.name',
                        'Statut' => fn ($order) => $order->state->title(),
                    ]" :rows="$latestOrders">
                    </x-table>
                </div>
            </div>



            {{--@can('manage', App\Models\AccessCard::class)
                <x-section-border></x-section-border>

                <livewire:access-cards.top-up-form :user="$user" />
            @endcan--}}

            @if($user->accessCard)
            <x-section-border></x-section-border>

            <div class="md:grid md:grid-cols-3 md:gap-6">
              <x-section-title>
                  <x-slot name="title">Historiques de rechargements</x-slot>
                  <x-slot name="description">Les differentes historiques de rechargement du collaborateur</x-slot>
              </x-section-title>

              <div class="mt-5 md:mt-0 md:col-span-2">
                  <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-2">
                      <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                          <div
                              class="p-3 mr-4 text-primary-500 bg-primary-100 rounded-full dark:text-primary-100 dark:bg-primary-500">
                              <x-icon name="card" class="w-8 h-8 text-info-600" />
                          </div>
                          <div>
                              <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                Nbr rechargement petit dejeuner
                              </p>
                              <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                  {{ $user->accessCard->countBreakfastReload() }}
                              </p>
                          </div>
                      </div>
                      <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                          <div
                              class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                              <x-icon name="card" class="w-8 h-8 text-primary-600" />
                          </div>
                          <div>
                              <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                  Nbr rechargement dejeuner
                              </p>
                              <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                {{ $user->accessCard->countLunchReload() }}
                              </p>
                          </div>
                      </div>
                  </div>
                  <div class="py-4">
                      <span class="font-bold">Derniers rechargements</span>
                  </div>
                  <x-table hover class="shadow bg-white" :columns="[
                      'Date' => fn ($history) => $history->created_at->format('d/m/Y'),
                      'Type quota' => fn($history) => $history->quota_type == 'breakfast' ? 'Petit dejeuner' : 'Dejeuner',
                      'Nbr de quota' => fn ($history) => $history->quota,
                  ]" :rows="$user->accessCard->reloadAccessCardHistory">
                  </x-table>
              </div>
              @endif
        @endif

    </section>
</x-app-layout>
