<x-app-layout>

    <x-section-header title="Menu">
        <x-slot name="actions">
            <a href="{{ route('dishes.index') }}" class="bg-accent-800 hidden md:flex px-6 py-2 font-semibold text-white rounded-md hover:bg-primary-800">
                Retour
            </a>
        </x-slot>
    </x-section-header>
    <hr class="mb-4">

<div>
    <div class="bg-white p-4 w-full mx-auto">

        <form action="" class="w-full">

            <div class="py-2 px-10">
                <!--   tip; mx-auto -- jagab ilusti keskele  -->
                <div class="bg-white p-4 w-full mx-auto">

                  <form action="" class="w-full">

                    <!--       flex - asjad korvuti, nb! flex-1 - element kogu ylejaanud laius -->
                    <div class="flex items-center mb-5">
                        <div class="w-full px-2">
                            <label for="date" class="inline-block px-2 text-right text-gray-600">Date : </label>
                            <label for="date" class="inline-block px-2 text-right font-bold text-gray-600">18/11/2021 </label>
                        </div>
                    </div>
                    <div class="flex items-center mb-5">
                        <div class="w-1/2 px-2">
                            <label for="entre" class="inline-block px-2 text-right text-gray-600">Entrée : </label>
                            <label for="entre" class="inline-block px-2 text-right font-bold text-gray-600">Crudité aux oeufs</label>
                        </div>
                        <div class="w-1/2 px-2">
                            <label for="plat1" class="inline-block px-2 text-right text-gray-600">Plat 1 :</label>
                            <label for="plat1" class="inline-block px-2 text-right font-bold text-gray-600">Sauce akpi poissons + Riz blanc</label>
                        </div>
                    </div>
                    <div class="flex items-center mb-5">
                        <div class="w-1/2 px-2">
                            <label for="plat2" class="inline-block px-2 text-right text-gray-600">Plat 2 : </label>
                            <label for="plat2" class="inline-block px-2 text-right font-bold text-gray-600">Emincée de poulet + tagriatelle</label>
                        </div>
                        <div class="w-1/2 px-2">
                            <label for="dessert" class="inline-block px-2 text-right text-gray-600">Déssert : </label>
                            <label for="dessert" class="inline-block px-2 text-right font-bold text-gray-600">fruits</label>
                        </div>
                    </div>



                    <div class="text-right py-6">
                      <button class="py-3 px-8 bg-red-700 hover:bg-opacity-50 text-white font-bold">Annuler</button>
                      <button class="py-3 px-8 bg-secondary-800 hover:bg-opacity-50 text-white font-bold">Modifier le menu</button>
                    </div>

                  </form>
                </div>
            </div>

        </form>
      </div>
</div>
</x-app-layout>
