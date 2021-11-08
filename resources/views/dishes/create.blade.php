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

          <!--       flex - asjad korvuti, nb! flex-1 - element kogu ylejaanud laius -->
          <div class="flex items-center mb-5">
              <div class="w-full px-2">
                  <label for="date" class="inline-block px-2 text-right font-bold text-gray-600">Date </label>
                  <input type="date" id="date" name="date" placeholder="Entrez la date" class="flex-1  rounded-md w-full mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
          </div>
          <div class="flex items-center mb-5">
              <div class="w-1/2 px-2">
                  <label for="entre" class="inline-block px-2 text-right font-bold text-gray-600">Entrée</label>
                  <input type="text" id="entre" name="entre" placeholder="Entrez le libellé  de l'entrée" class="flex-1 w-full rounded-md mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
              <div class="w-1/2 px-2">
                  <label for="plat1" class="inline-block px-2 text-right font-bold text-gray-600">Plat 1</label>
                  <input type="text" id="plat1" name="email" placeholder="Entrez le libéllé du plat 1" class="flex-1  rounded-md w-full mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
          </div>
          <div class="flex items-center mb-5">
              <div class="w-1/2 px-2">
                  <label for="plat2" class="inline-block px-2 text-right font-bold text-gray-600">Plat 2</label>
                  <input type="text" id="plat2" name="plat2" placeholder="Entrez le libéllé du plat 2" class="flex-1 w-full rounded-md mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
              <div class="w-1/2 px-2">
                  <label for="dessert" class="inline-block px-2 text-right font-bold text-gray-600">Déssert</label>
                  <input type="text" id="dessert" name="society" placeholder="Entrez le libéllé du dessert" class="flex-1  rounded-md w-full mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
          </div>



          <div class="text-right">
            <button class="py-3 px-8 bg-red-700 hover:bg-opacity-50 text-white font-bold">Annuler</button>
            <button class="py-3 px-8 bg-secondary-800 hover:bg-opacity-50 text-white font-bold">Créer le menu</button>
          </div>

        </form>
      </div>
</div>
</x-app-layout>
