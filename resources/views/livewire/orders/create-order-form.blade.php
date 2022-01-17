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
                                        @if ($menu->canBeOrdered() && $dish->dishType->is_orderable && !in_array($menu->id, array_keys($selectedDishes)))
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
                <div class="sticky mt-10 space-y-8 top-32">
                    <div class="bg-white shadow py-2 px-4 flex flex-col space-y-4">
                        <h3 class="font-semibold text-lg flex items-center justify-between">
                            <span>Mon panier</span>
                            <span class="text-sm">
                                <x-icon-cde
                                    class="inline-block w-6 h-6 text-primary-900 hover:opacity-50 border-b border-primary-900" />
                            </span>
                        </h3>
                        <div>
                            <ul class="flex flex-col space-y-2">
                                @forelse ($selectedDishes as $menuId => $item)
                                    <li class="flex items-center space-x-1">
                                        <span class="inline-block truncate">
                                            {{ $menus->where('id', $menuId)->first()->served_at->format('d/m/Y') }} -
                                            {{ $item['name'] }}
                                        </span>
                                        <div x-data="{ tooltip: 'Rétirer du panier'}">
                                            <button x-on:click="$wire.removeDish(@js($menuId))" x-tooltip="tooltip"
                                                class="text-error">
                                                <x-icon name="trash" class="inline-block w-6 h-6 stroke-current" />
                                            </button>
                                        </div>
                                    </li>
                                @empty
                                    <p class="text-center">
                                        Votre panier est vide.
                                    </p>
                                @endforelse
                            </ul>
                        </div>
                        @if ($userAccessCard && $userAccessCard->quota_lunch > 0)
                            <button class="btn btn-sm btn-primary w-full" wire:click="saveOrder">
                                Commander
                            </button>
                        @endif
                        @error('selectedDishes')
                            <label class="label">
                                <span class="label-text-alt text-red-600">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
