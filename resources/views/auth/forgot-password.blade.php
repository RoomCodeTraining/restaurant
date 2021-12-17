<x-guest-layout>
    <div class="w-full h-screen  md:flex lg:flex xl:flex">
        <div class="bg-white flex justify-center flex-col lg:w-5/12 xl:w-5/12 md:w-6/12 shadow-lg">
            <div class="flex font-bold justify-center my-8">
                <img class="h-auto w-1/3" src="{{asset('images/logo-ciprel-vf.png')}}">
            </div>
            <div class="text-xl text-center text-gray-700 px-10 mb-8">
                {{ __('Mot de passe oublié') }}
            </div>
            <div class="w-full text-center px-16">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                <form class="w-full bg-white rounded-lg"  method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="px-12 pb-4">
                        <div class="w-full mb-2">
                            <div class="flex items-center">
                                <i class="ml-3 fill-current text-gray-400 text-xs z-10 fas fa-envelope"></i>
                                <input type='email' id="email" placeholder="Entrez votre adresse mail" type="email" name="email" :value="old('email')" class="-mx-6 px-8  w-full bg-opacity-25 rounded py-2 text-gray-700 focus:outline-none bg-gray-100" required autofocus  />
                            </div>
                        </div>
                        <button type="submit" class="w-full my-4 py-3 font-bold bg-primary-900 hover:bg-secondary-800 text-gray-100  focus:outline-none">Reinitialisez votre mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
        <img src="{{asset('images/login_image.jpg')}}" alt="background" class="object-cover object-center h-screen w-full md:w-6/12 lg:w-7/12 xl:w-7/12">
    </div>
</x-guest-layout>
