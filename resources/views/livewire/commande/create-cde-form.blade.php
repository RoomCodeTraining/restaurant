<div>
    <div class="bg-white p-4 w-full mx-auto">

        <form action="" class="w-full">

          <!--       flex - asjad korvuti, nb! flex-1 - element kogu ylejaanud laius -->
          <div class="flex items-center mb-5">
              <div class="w-1/2 px-2">
                  <label for="Matricule" class="inline-block px-2 text-right font-bold text-gray-600">Matricule</label>
                  <input type="text" id="matricule" name="matricule" placeholder="Entrez le matricule" class="flex-1 w-full rounded-md mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
              <div class="w-1/2 px-2">
                  <label for="fistname" class="inline-block px-2 text-right font-bold text-gray-600">Nom</label>
                  <input type="text" id="firstname" name="firstname" placeholder="Entrez le nom" class="flex-1  rounded-md w-full mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
          </div>
          <div class="flex items-center mb-5">
              <div class="w-1/2 px-2">
                  <label for="lastnname" class="inline-block px-2 text-right font-bold text-gray-600">Prénoms</label>
                  <input type="text" id="lastname" name="lastname" placeholder="Entrez le prénoms" class="flex-1 w-full rounded-md mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
              <div class="w-1/2 px-2">
                  <label for="email" class="inline-block px-2 text-right font-bold text-gray-600">Email</label>
                  <input type="email" id="email" name="email" placeholder="Entrez l'E-mail" class="flex-1  rounded-md w-full mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
          </div>
          <div class="flex items-center mb-5">
              <div class="w-1/2 px-2">
                  <label for="contact" class="inline-block px-2 text-right font-bold text-gray-600">Contact</label>
                  <input type="tel" id="contact" name="contact" placeholder="Entrez le contact" class="flex-1 w-full rounded-md mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
              <div class="w-1/2 px-2">
                  <label for="society" class="inline-block px-2 text-right font-bold text-gray-600">Société</label>
                  <input type="text" id="society" name="society" placeholder="Entrez la société" class="flex-1  rounded-md w-full mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
          </div>
          <div class="flex items-center mb-5">
              <div class="w-1/2 px-2">
                  <label for="Departement" class="inline-block px-2 text-right font-bold text-gray-600">Département</label>
                  <input type="text" id="departure" name="departure" placeholder="Entrez le département" class="flex-1 w-full rounded-md mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
              <div class="w-1/2 px-2">
                  <label for="categorie" class="inline-block px-2 text-right font-bold text-gray-600">Catégorie Professionnelle</label>
                  <input type="text" id="categorie" name="categorie" placeholder="Entrez la catégorie professionnelle" class="flex-1  rounded-md w-full mx-2 py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
              </div>
          </div>
          <div class="flex items-center mb-5">
              <div class="w-1/2 px-2">
                  <label for="type" class="inline-block px-2 text-right font-bold text-gray-600">Type</label>
                  <select class="flex-1 w-full py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
                      <option>Choisir le type de collaborateur</option>
                      <option>Agent CIPREL</option>
                      <option>Agent non CIPREL</option>
                      <option>Stagiaire</option>
                      <option> Invité</option>
                  </select>
              </div>
              <div class="w-1/2 px-2">
                  <label  for="profil" class="inline-block px-2 text-right font-bold text-gray-600">Profil</label>
                  <select class="flex-1 w-full py-2 border-b-2 border-gray-400 focus:border-green-400 text-gray-600 placeholder-gray-400 outline-none">
                      <option>Choisir le profil</option>
                      <option>Utilisateur</option>
                      <option>Admin cantine</option>
                      <option>Admin RH</option>
                      <option>Comptable</option>
                      <option>Opérateur cantine</option>
                      <option>Admin fonctionnel</option>
                  </select>
              </div>
          </div>
          <div class="block items-center mb-5">
              <!--         tip - here neede inline-block , but why? -->
              <label for="file" class="inline-block text-right px-2  font-bold text-gray-600">Photo</label>
              <input type="file" id="file" name="file" placeholder="Choisir la photo"
                     class="flex-1  w-full py-2 border-b-2 border-gray-400 focus:border-green-400
                            text-gray-600 placeholder-gray-400
                            outline-none">
            </div>


          <div class="text-right">
            <button class="py-3 px-8 bg-red-700 hover:bg-opacity-50 text-white font-bold">Annuler</button>
            <button class="py-3 px-8 bg-secondary-800 hover:bg-opacity-50 text-white font-bold">Créer</button>
          </div>

        </form>
      </div>

      <x-form-card>
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-2 md:gap-4">
                <div class="col-span-8 md:col-span-4 form-control">
                    <label class="label">
                        <span class="label-text">Nom</span>
                    </label>
                    <input class="input input-bordered" type="text" wire:model.defer="state.name">
                    @error('state.name')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                   <div class="col-span-8 md:col-span-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Choississez un type de plat</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="state.dish_type_id">
                            <option selected="selected">Veuillez choisir</option>
                            @foreach ($dishTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('state.dish_type_id')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="col-span-8 md:col-span-12 form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea class="input input-bordered" type="text" wire:model.defer="state.description"></textarea>
                    @error('state.description')
                        <label class="label">
                            <span class="label-text-alt text-red-600">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                <button class="md:hidden btn">
                    Retour
                </button>
                <button class="btn btn-primary" wire:target="saveDish" type="submit" wire:loading.attr="disabled" wire:loading.class="loading">
                    Enregistrer
                </button>
            </div>
        </x-slot>
    </x-form-card>
</div>

</div>
