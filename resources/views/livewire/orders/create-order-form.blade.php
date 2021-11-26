<div>
    <div>
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <main class="lg:col-span-9 xl:col-span-9">
                <!-- Beginnig of Main Content -->
                <div class="relative flex flex-col space-y-4 w-full">
                    @foreach ($menus as $menu)
                        <div class="flex flex-col space-y-2 z-9999">
                            <h3 class="text-lg font-semibold">Menu du {{ $menu->served_at->format('d/m/Y') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-white shadow p-4 flex flex-col space-y-2 items-center justify-center">
                                    <img src="{{ asset('images/entree1.png') }}" class="w-24">
                                    <div class="product__info">
                                        <p class="font-medium text-primary-800">
                                            {{ $menu->starterDish->name }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="product-price layout-vertical-tablet text-center items-center">
                                            <span class="text-sm">
                                                Entrée
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white shadow p-4 flex flex-col space-y-2 items-center justify-center">
                                    <img src="{{ asset('images/plat1.png') }}" class="w-24">
                                    <div class="product__info">
                                        <p class="font-medium text-primary-800">
                                            {{ $menu->mainDish->name }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="product-price layout-vertical-tablet">
                                            <span class="text-sm">
                                                Plat principal
                                            </span>
                                        </div>
                                        @if (now()->lessThanOrEqualTo($menu->served_at))
                                            @if (in_array($menu->id, array_keys($selectedDishes)))
                                                <div x-data="{ tooltip: 'Rétirer de votre commande' }">
                                                    <button wire:click="removeDish({{ $menu->id }})"
                                                        x-tooltip="tooltip" class="p-2 text-error">
                                                        <x-icon-minus class="inline-block w-6 h-6 stroke-current" />
                                                    </button>
                                                </div>
                                            @else
                                                <div x-data="{ tooltip: 'Ajouter à votre commande' }">
                                                    <button
                                                        wire:click="addDish({{ $menu->id }}, {{ $menu->mainDish }})"
                                                        x-tooltip="tooltip" class="p-2 text-secondary-700">
                                                        <x-icon-add class="inline-block w-6 h-6 stroke-current" />
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-white shadow p-4 flex flex-col space-y-2 items-center justify-center">
                                    <img src="{{ asset('images/plat2.png') }}" class="w-24">
                                    <div class="product__info">
                                        <p class="font-medium text-primary-800">
                                            {{ $menu->secondDish->name }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="product-price layout-vertical-tablet">
                                            <span class="text-sm">
                                                Plat principal
                                            </span>
                                        </div>
                                        @if (now()->lessThanOrEqualTo($menu->served_at))
                                            @if (in_array($menu->id, array_keys($selectedDishes)))
                                                <div x-data="{ tooltip: 'Rétirer de votre commande' }">
                                                    <button wire:click="removeDish({{ $menu->id }})"
                                                        x-tooltip="tooltip" class="p-2 text-error">
                                                        <x-icon-minus class="inline-block w-6 h-6 stroke-current" />
                                                    </button>
                                                </div>
                                            @else
                                                <div x-data="{ tooltip: 'Ajouter à votre commande' }">
                                                    <button
                                                        wire:click="addDish({{ $menu->id }}, {{ $menu->secondDish }})"
                                                        x-tooltip="tooltip" class="p-2 text-secondary-700">
                                                        <x-icon-add class="inline-block w-6 h-6 stroke-current" />
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-white shadow p-4 flex flex-col space-y-2 items-center justify-center">
                                    <img src="{{ asset('images/dessert1.png') }}" class="w-24">
                                    <div class="product__info">
                                        <p class="font-medium text-primary-800">
                                            {{ $menu->dessertDish->name }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="product-price layout-vertical-tablet text-center items-center">
                                            <span class="text-sm">
                                                Déssert
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- End of Main Content -->
            </main>
            <aside class=" xl:block xl:col-span-3">
                <div class="sticky top-4 mt-10 space-y-4">
                    <div class="bg-white shadow p-4 flex flex-col space-y-2">
                        <h3 class="font-semibold text-lg flex items-center justify-between">
                            <span>Mon panier</span>
                            <span class="text-sm">
                                <x-icon-cde class="inline-block w-6 h-6 text-primary-900 hover:opacity-50 border-b border-primary-900" />
                            </span>
                        </h3>
                        <div>
                            <ul class="list-disc ml-4">
                                @forelse ($selectedDishes as $menuId => $item)
                                    <li>
                                        {{ $item['name'] }} pour le
                                        {{ $menu->where('id', $menuId)->first()->served_at->format('d/m/Y') }}
                                    </li>
                                @empty
                                    <p class="text-center">
                                        Votre panier est vide
                                    </p>
                                @endforelse
                            </ul>
                        </div>
                        @if ($userAccessCard && $userAccessCard->quota_lunch > 0)
                            <div class="">
                                <button class="btn btn-sm btn-primary w-full my-2" wire:click="saveOrder">Commander</button>
                            </div>
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
