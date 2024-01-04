<x-guest-layout>
    <!-- Page Container -->
    <div id="page-container" class="flex flex-col mx-auto w-full min-h-screen bg-gray-100">
        <!-- Page Content -->
        <div class="w-full h-screen  md:flex lg:flex xl:flex">
           <img src="{{ asset('images/login_image.jpg') }}" alt="background"
                class="object-cover object-center h-screen w-full md:w-6/12 lg:w-7/12 xl:w-7/12">
            <div class="bg-white flex justify-center flex-col lg:w-5/12 xl:w-5/12 md:w-6/12 w-full shadow-lg">
                <div class="flex font-bold justify-center mb-6 mt-2">
                    {{-- <img class="h-auto w-1/3" src="{{ asset('images/logo-ciprel-vf.png') }}"> --}}
                </div>
                <div class="w-full text-center px-16">
                    <form class="w-full bg-white rounded-lg" method="POST" action="{{ route('login') }}">
                        @csrf
                        <h2 class="text-xl text-center text-gray-700 my-4 mb-8 px-18">
                            Bienvenue, veuillez-vous connecter à votre compte
                        </h2>
                        <x-auth-validation-errors />
                        @include('partials.flasher')
                        <div class="px-2 pb-4">
                            <div class="w-full mt-8 mb-4">
                                <div class="flex items-center">
                                    <i
                                        class="ml-3 fill-current text-gray-400 text-xs z-10 fas fa-envelope-open-text"></i>
                                    <input type="text" id="tk-pages-sign-in-email" placeholder="Entrez votre email"
                                        name="email" :value="old('email')" :value="old('email')"
                                        class="-mx-6 px-8 w-full rounded py-2 bg-opacity-25 text-gray-600 focus:outline-none bg-gray-100"
                                        required autofocus />
                                </div>
                            </div>

                            <div class="w-full mt-4">
                                <div class="flex items-center">
                                    <i class='ml-3 fill-current text-gray-400 text-xs z-10 fas fa-lock'></i>
                                    <input type="password" id="tk-pages-sign-in-password" name="password"
                                        placeholder="Entrez votre mot de passe"
                                        class="-mx-6 px-8 w-full rounded py-2 bg-opacity-25 text-gray-600 focus:outline-none bg-gray-100"
                                        required autocomplete="current-password" />
                                </div>
                            </div>
                            <div class="flex w-full items-center justify-between mt-4 mb-12">
                                <div class="">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox" checked
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            name="remember">
                                        <span class="ml-2 text-sm text-gray-600">{{ __(' Resté connecté ?') }}</span>
                                    </label>
                                </div>
                                <div class="">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}"
                                            class="text-sm text-gray-500 bg-opacity-25">
                                            Mot de passe oublié?
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-3 font-medium bg-primary-900 text-gray-100  focus:outline-none rounded-lg uppercase">
                                Connectez-vous
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
