<x-app-layout>
    <section>
        <x-section-header title="Détails">
            <x-slot name="actions">
                <a href="{{ route('dishes.index') }}" class="btn btn-secondary">
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
                                        <dt class="text-sm font-medium text-gray-500">Libellé</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $dish->name }}</dd>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $dish->dishType->name }}</dd>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-8">
                                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0">
                                    <div class="w-full md:w-full">
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="text-sm font-normal text-gray-900">{{ $dish->description ?? '' }}</dd>
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
