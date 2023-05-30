<div>
    <div>
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <main class="lg:col-span-9 xl:col-span-9">
                <div class="flex flex-col space-y-8 w-full">
                    @foreach ($menus as $menu)
                        <div class="flex flex-col space-y-2">
                            <h3 class="text-lg md:text-xl font-semibold {{ $menu->served_at->isCurrentDay() ? 'text-primary-800' : '' }}">
                                Menu du {{ \Carbon\Carbon::parse($menu->served_at)->locale('FR_fr')->isoFormat('dddd D MMMM YYYY') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                @foreach ($menu->dishes->sortBy('position') as $dish)
                                    <x-dish-card :dish="$dish" :menu="$menu">
                                        @if (!auth()->user()->can_order_two_dishes && $menu->canBeOrdered() && $dish->dishType->is_orderable && !in_array($menu->id, array_keys($selectedDishes)))
                                            <div x-data="{ tooltip: 'Ajouter au panier' }">
                                                <button x-on:click="$wire.addDish(@js($menu->id), @js($dish->id))"
                                                    x-tooltip="tooltip" class="p-2 text-green-500">
                                                    <x-icon-add class="inline-block w-6 h-6 stroke-current" />
                                                </button>
                                            </div>
                                            @elseif(auth()->user()->can_order_two_dishes && $dish->dishType->is_orderable)
                                            <div x-data="{ tooltip: 'Ajouter au panier' }">
                                                <button x-on:click="$wire.addDish(@js($menu->id), @js($dish->id))"
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
               <x-cart :userAccessCard='$userAccessCard' :selectedDishes='$selectedDishes' :menus='$menus'/>
            </aside>
        </div>
    </div>
</div>
