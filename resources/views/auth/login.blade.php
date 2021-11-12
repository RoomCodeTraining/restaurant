<x-guest-layout>

    {{-- <div class="w-full h-screen flex">
        <div class="bg-white flex justify-center flex-col w-6/12 shadow-lg">
            <div class="flex font-bold justify-center mb-6 mt-2">
                <img class="h-auto w-1/3" src="{{ asset('images/logo-ciprel-vf.png') }}">
            </div>
            <div class="w-full text-center px-16">
                <form class="w-full bg-white rounded-lg"  method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <h2 class="text-xl text-center text-gray-700 my-4 mb-8 px-18">Bienvenue, Veuillez-vous connecter à votre compte </h2>
                    <div class="px-12 pb-4">
                        <div class="w-full mt-8 mb-4">
                            <div class="flex items-center">
                                <i class="ml-3 fill-current text-gray-400 text-xs z-10 fas fa-envelope-open-text"></i>
                                <input type='email' id="email" placeholder="Entrez votre adresse mail" type="email" name="email" :value="old('email')" class="-mx-6 px-8  w-full bg-opacity-25 rounded py-2 text-gray-700 focus:outline-none bg-primary-400" required autofocus  />
                            </div>
                        </div>
                        <div class="w-full mt-4">
                            <div class="flex items-center">
                                <i class='ml-3 fill-current text-gray-400 text-xs z-10 fas fa-lock'></i>
                                <input type='password' id="password" placeholder="Entrez votre mot de passe" type="password"  name="password" class="-mx-6 px-8 w-full rounded py-2 text-gray-700 bg-opacity-25 focus:outline-none bg-primary-400" required autocomplete="current-password" />
                            </div>
                        </div>
                        <div class="flex items-center mt-2 mb-12">
                            <div class="w-1/2">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" name="remember">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                                </label>
                            </div>
                            <div class="w-1/2">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs text-gray-500 bg-opacity-25">Mot de passe oublié?</a>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 font-medium bg-primary-900 text-gray-100  focus:outline-none">Connectez-vous</button>
                    </div>
                </form>
            </div>
        </div>
        <img src="{{asset('images/loginimg.jpg')}}" alt="background" class="object-cover object-center h-screen w-6/12">
    </div> --}}

    <!-- Page Container -->
    <div id="page-container" class="flex flex-col mx-auto w-full min-h-screen bg-gray-100">
        <!-- Page Content -->
       {{--  <main id="page-content" class="flex flex-auto md:flex-col max-w-full">


             <div class="min-h-screen flex flex-col bg-cover bg-bottom" style="background-image: url('/images/repas1.svg'); background-size: cover; background-position: center center;">

                <div class="flex flex-grow md:w-8/12 lg:w-5/12 xl:w-4/12 bg-white shadow-xl">
                    <div class="flex flex-col p-8 lg:p-16 xl:p-20 w-full">

                        <div class="flex-grow flex items-center">
                            <div class="w-full max-w-lg mx-auto space-y-10">

                                <div>
                                    <h1 class="text-4xl font-bold inline-flex items-center mb-1 h-32 justify-center">
                                        <img class="h-full" src="{{ asset('images/logo-ciprel-vf.png') }}">
                                    </h1>
                                    <p class="text-gray-500">
                                        Bienvenue, veuillez vous connectez à votre plateforme
                                    </p>

                                <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                                    @csrf
                                    <div class="space-y-1">
                                        <label for="tk-pages-sign-in-email" class="font-medium">Email</label>
                                        <input
                                            class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                            type="email" id="tk-pages-sign-in-email" placeholder="Entrez votre email"
                                            name="email" :value="old('email')" required autofocus />
                                    </div>
                                    <div class="space-y-1">
                                        <label for="tk-pages-sign-in-password" class="font-medium">
                                            Mot de passe
                                        </label>
                                        <input
                                            class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                            type="password" id="tk-pages-sign-in-password" required
                                            autocomplete="current-password" name="password"
                                            placeholder="Entrez votre mot de passe" />
                                    </div>
                                    <div>
                                        <button type="submit"
                                            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none w-full px-4 py-3 leading-6 rounded border-primary-700 bg-primary-700 text-white hover:text-white hover:bg-primary-800 hover:border-primary-800 focus:ring focus:ring-primary-500 focus:ring-opacity-50 active:bg-primary-700 active:border-primary-700">
                                            Se connecter
                                        </button>
                                        <div
                                            class="space-y-2 sm:flex sm:items-center sm:justify-between sm:space-x-2 sm:space-y-0 mt-4">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="remember"
                                                    class="border border-gray-300 rounded h-4 w-4 text-primary-500 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" />
                                                <span class="ml-2">
                                                    Resté connecté ?
                                                </span>
                                            </label>
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}"
                                                    class="font-medium inline-block text-primary-600 hover:text-primary-400">
                                                    Mot de passe oublié ?
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main> --}}

        <div class="w-full h-screen  md:flex lg:flex xl:flex">
            <div class="bg-white flex justify-center flex-col lg:w-5/12 xl:w-5/12 md:w-6/12 w-full shadow-lg">
                <div class="flex font-bold justify-center mb-6 mt-2">
                    <img class="h-auto w-1/3" src="{{ asset('images/logo-ciprel-vf.png') }}">
                </div>
                <div class="w-full text-center px-16">
                    <form class="w-full bg-white rounded-lg"  method="POST" action="{{ route('login') }}">
                        @csrf
                        <h2 class="text-xl text-center text-gray-700 my-4 mb-8 px-18">Bienvenue, Veuillez-vous connecter à votre compte </h2>
                        <x-auth-validation-errors />
                        <div class="px-2 pb-4">
                            <div class="w-full mt-8 mb-4">
                                <div class="flex items-center">
                                    <i class="ml-3 fill-current text-gray-400 text-xs z-10 fas fa-envelope-open-text"></i>
                                    <input id="tk-pages-sign-in-email" placeholder="Entrez votre email" name="email" :value="old('email')" :value="old('email')" class="-mx-6 px-8  w-full bg-opacity-25 rounded py-2 text-gray-600 focus:outline-none bg-gray-100" required autofocus  />
                                </div>
                            </div>
                            <div class="w-full mt-4">
                                <div class="flex items-center">
                                    <i class='ml-3 fill-current text-gray-400 text-xs z-10 fas fa-lock'></i>
                                    <input type="password" id="tk-pages-sign-in-password" name="password" placeholder="Entrez votre mot de passe" class="-mx-6 px-8 w-full rounded py-2 bg-opacity-25 text-gray-600 focus:outline-none bg-gray-100" required autocomplete="current-password" />
                                </div>
                            </div>
                            <div class="flex items-center mt-2 mb-12">
                                <div class="w-1/2">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                        <span class="ml-2 text-xs text-gray-600">{{ __(' Resté connecté ?') }}</span>
                                    </label>
                                </div>
                                <div class="w-1/2">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-xs text-gray-500 bg-opacity-25">Mot de passe oublié?</a>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 font-medium bg-primary-900 text-gray-100  focus:outline-none">Connectez-vous</button>
                        </div>
                    </form>
                </div>
            </div>
            <img src="{{asset('images/loginimg.jpg')}}" alt="background" class="object-cover object-center h-screen w-full md:w-6/12 lg:w-7/12 xl:w-7/12">
        </div>

    </div>


</x-guest-layout>
