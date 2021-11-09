<x-app-layout>
    <section>
        <x-section-header title="Détails du menu du {{ $menu->served_at }}">
            <x-slot name="actions">
                <a href="{{ route('menus.index') }}" class="btn btn-secondary">
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
                                        <dt class="text-sm font-medium text-gray-500">Entrée</dt></dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $menu->starterDish->name }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Plat 1</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $menu->mainDish->name }}</dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Plat 2</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ optional($menu->secondDish)->name ?? 'Aucun' }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Dessert</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $menu->dessertDish->name }}</dd>
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
