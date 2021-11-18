<div x-data="{selectedMenuId: @entangle('selectedMenuId')}">
    <x-form-card submit="saveOrder">
        <x-slot name="form">
            <fieldset>
                <legend class="text-lg font-medium text-gray-900">Menu de la semaine (cliquez sur un menu)</legend>
                <div class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4" >
                        @foreach ($menus as $menu)
                            <div class="col-span-1 border-2 border-primary-500 rounded-md hover:shadow-lg  bg-opacity-50 hover:rounded-md p-2 shadow cursor-pointer bg-center bg-cover bg-no-repeat" style="background-image: url(&quot;https://res.cloudinary.com/moodgiver/image/upload/v1623360651/jean-philippe-delberghe-1400011-unsplash_a8qscz.jpg&quot;);"
                                :class="{'border-4 border-accent-900 rounded-md': selectedMenuId == '{{ $menu->id }}'}"
                                x-on:click="selectedMenuId = '{{ $menu->id }}'">
                                <div id="moka-ix3cs" class="bg-gray-400  w-full shadow-md p-1 h-auto rounded-lg blur-3 bg-opacity-40 flex flex-col col-span-12">
                                <div class="flex items-center space-x-2">
                                    <x-icon-calendar class="h-6 w-6 text-gray-50" />
                                    <span class="font-medium text-lg text-gray-50">Menu du {{ $menu->served_at }}</span>
                                </div>
                                <hr>
                                <div class="my-4">
                                    <h4 class="font-medium flex items-center space-x-2">
                                        <svg class="h-12 w-12 text-primary-900 border-r border-primary-900" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32">
                                            <path
                                                d="M26 15a6.003 6.003 0 0 0-3.107-5.253A3.98 3.98 0 0 0 24 7h-2a2.002 2.002 0 0 1-2 2a6.004 6.004 0 0 0-5.995 5.892A7 7 0 0 1 12 10a3.996 3.996 0 0 0-3-3.858V4H7v2.142A3.996 3.996 0 0 0 4 10v5H2v1a14 14 0 0 0 28 0v-1zm-6-4a4.005 4.005 0 0 1 4 4h-8a4.005 4.005 0 0 1 4-4zM6 10a2 2 0 1 1 4 0a8.991 8.991 0 0 0 1.532 5H6zm10 18A12.017 12.017 0 0 1 4.041 17H27.96A12.017 12.017 0 0 1 16 28z"
                                                fill="currentColor"></path>
                                        </svg>
                                        <span class="text-white px-4 py-1 bg-primary-900 rounded w-28">Entrées :</span>
                                    </h4>
                                    <ul class="list-none ml-20 text-gray-50 font-semibold">
                                        <li>{{ $menu->starterDish->name }}</li>
                                    </ul>
                                </div>
                                <div class="my-4">
                                    <h4 class="font-medium flex items-center space-x-2">
                                        <x-icon-plat class="h-12 w-12 text-gray-50 border-r border-gray-50" />
                                        <span class="text-primary-900 px-4 py-1 bg-gray-50 blur-2 rounded w-28">Plats :</span>
                                    </h4>
                                    <ul class="list-none ml-20 text-gray-50 font-semibold">
                                        <li>{{ $menu->mainDish->name }}</li>
                                        <li>{{ $menu->secondDish->name }}</li>
                                    </ul>
                                </div>
                                <div class="my-4">
                                    <h4 class="font-medium flex items-center space-x-2">
                                        <svg class="h-12 w-12 text-secondary-900 border-r border-secondary-900" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="32" d="M352 256l-96 224l-62-145">
                                            </path>
                                            <path
                                                d="M299.42 223.48C291.74 239.75 275.18 252 256 252c-13.1 0-27-5-33.63-9.76C216.27 237.87 208 240 208 250v62a24.07 24.07 0 0 1-24 24h0a24.07 24.07 0 0 1-24-24v-56h-2c-35.35 0-62-28.65-62-64a64 64 0 0 1 64-64h8v-8a88 88 0 0 1 176 0v8h8a64 64 0 0 1 0 128c-21.78 0-42-13-52.59-32.51z"
                                                fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="32"></path>
                                        </svg>
                                        <span class="text-white px-4 py-1 bg-secondary-900 rounded w-28">Desserts :</span>
                                    </h4>
                                    <ul class="list-none ml-20 text-gray-50 font-semibold">
                                        <li>{{ $menu->dessertDish->name }}</li>
                                    </ul>
                                </div>
                                <button value="button" class="hover:text-gray-300 bg-accent-900 text-white hover:bg-black w-56 p-4 text-2xl font-bold" id="moka-8pwrq" style="font-family: Barlow;">COMMANDER</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </fieldset>
            @if ($selectedMenu)
                <fieldset class="mt-4">
                <legend class="text-lg font-medium text-gray-900">Choix du plat</legend>
                <div class="mt-4 border-t border-b border-gray-200 divide-y divide-gray-200">
                    <div class="col-span-8 md:col-span-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">Plat principal</span>
                            </label>
                            <select class="select select-bordered w-full" wire:model.defer="dishId">
                                <option selected="selected">Veuillez choisir</option>
                                <option value="{{ $selectedMenu->mainDish->id }}">{{ $selectedMenu->mainDish->name }}</option>
                                <option value="{{ $selectedMenu->secondDish->id }}">{{ $selectedMenu->secondDish->name }}</option>
                            </select>
                        </div>
                        @error('dishId')
                            <label class="label">
                                <span class="label-text-alt text-red-600">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </fieldset>
            @endif
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn-sm">
                    Retour
                </button>
                <button class="btn-sm btn-primary" wire:target="saveDish" type="submit" wire:loading.attr="disabled"
                    wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>
