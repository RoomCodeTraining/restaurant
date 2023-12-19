<div>
    @if (app()->environment('production'))
        <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">Fonctionnalité en en cours d'implementation'</span> Cette partie de commande concernera uniquement les collaborateurs de la centrale qui ont droit à deux plats.
            </div>
        </div>
    @else
        <div>
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <main class="lg:col-span-9 xl:col-span-9">
                    <div class="flex flex-col space-y-8 w-full">
                        @foreach ($menus as $menu)
                            <div class="flex flex-col space-y-2">
                                <h3
                                    class="text-lg md:text-xl font-semibold {{ $menu->served_at->isCurrentDay() ? 'text-primary-800' : '' }}">
                                    Menu du
                                    {{ \Carbon\Carbon::parse($menu->served_at)->locale('FR_fr')->isoFormat('dddd D MMMM YYYY') }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                    @foreach ($menu->dishes->sortBy('position') as $dish)
                                        <x-dish-card :dish="$dish" :menu="$menu">
                                            @if (
                                                !auth()->user()->can_order_two_dishes &&
                                                    $menu->canBeOrdered() &&
                                                    $dish->dishType->is_orderable &&
                                                    !in_array($menu->id, array_keys($selectedDishes)))
                                                <div x-data="{ tooltip: 'Ajouter au panier' }">
                                                    <button
                                                        x-on:click="$wire.addDish(@js($menu->id), @js($dish->id))"
                                                        x-tooltip="tooltip" class="p-2 text-green-500">
                                                        <x-icon-add class="inline-block w-6 h-6 stroke-current" />
                                                    </button>
                                                </div>
                                            @elseif(auth()->user()->can_order_two_dishes && $dish->dishType->is_orderable)
                                                <div x-data="{ tooltip: 'Ajouter au panier' }">
                                                    <button
                                                        x-on:click="$wire.addDish(@js($menu->id), @js($dish->id))"
                                                        x-tooltip="tooltip" class="p-2 text-green-500">
                                                        <x-icon-add class="inline-block w-6 h-6 stroke-current" />
                                                    </button>
                                                </div>
                                            @endif
                                        </x-dish-card>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </main>
                <aside class="xl:block xl:col-span-3">
                    <x-cart :userAccessCard='$userAccessCard' :selectedDishes='$selectedDishes' :menus='$menus' />
                </aside>
            </div>
        </div>
    @endif
</div>
