@props(['userAccessCard', 'selectedDishes', 'menus'])
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
          @if(! auth()->user()->can_order_two_dishes)
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
          @else
            @forelse ($selectedDishes as $menuId => $item)
                   @foreach($item as $dish)
                    <li class="flex items-center space-x-1">
                        <span class="inline-block truncate">
                            {{ $menus->where('id', $menuId)->first()->served_at->format('d/m/Y') }} -
                            {{ $dish['name'] }}
                        </span>
                        <div x-data="{ tooltip: 'Rétirer du panier'}">
                            <button x-on:click="$wire.removeDish(@js($menuId))" x-tooltip="tooltip"
                                class="text-error">
                                <x-icon name="trash" class="inline-block w-6 h-6 stroke-current" />
                            </button>
                        </div>
                    </li>
                   @endforeach
                @empty
                    <p class="text-center">
                        Votre panier est vide.
                    </p>
                @endforelse
            </ul>
          @endif
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
