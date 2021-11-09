<x-guest-layout>
    <div id="page-container" class="flex flex-col mx-auto w-full min-h-screen bg-gray-100">
        <!-- Page Content -->
        <main id="page-content" class="flex flex-auto flex-col max-w-full">
            <div class="min-h-screen flex flex-col bg-cover bg-bottom"
                style="background-image: url('/images/loginimg.jpg'); background-size: cover; background-position: center center;">
                <!-- Sign In Section -->
                <div class="flex flex-grow md:w-8/12 lg:w-5/12 xl:w-4/12 bg-white shadow-xl">
                    <div class="flex flex-col p-8 lg:p-16 xl:p-20 w-full">
                        <!-- Sign In Content -->
                        <div class="flex-grow flex items-center">
                            <div class="w-full max-w-lg mx-auto space-y-10">
                                <!-- Header -->
                                <div>
                                    <h1 class="text-4xl font-bold inline-flex items-center mb-1 h-32 justify-center">
                                        <img class="h-full" src="{{ asset('images/logo-ciprel-vf.png') }}">
                                    </h1>
                                    <p class="text-gray-500">
                                        Bienvenue, veuillez vous connectez à votre plateforme
                                    </p>
                                </div>
                                <!-- END Header -->

                                <!-- Sign In Form -->
                                {{-- <form method="POST">
                                    @csrf

                                    <input type="hidden" name="email" value="{{ $user->email }}" />

                                    <div>
                                        <label for="password">{{ __('Password') }}</label>

                                        <div>
                                            <input id="password" type="password"
                                                class="@error('password') is-invalid @enderror" name="password" required
                                                autocomplete="new-password">

                                            @error('password')
                                                <span>
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>

                                        <div>
                                            <input id="password-confirm" type="password" name="password_confirmation"
                                                required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div>
                                        <button type="submit">
                                            {{ __('Save password and login') }}
                                        </button>
                                    </div>
                                </form> --}}
                                <form method="POST" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $user->email }}" />

                                    <div class="space-y-1">
                                        <label for="tk-pages-sign-in-password" class="font-medium">
                                            Mot de passe
                                        </label>
                                        <input
                                            class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                            type="password" id="tk-pages-sign-in-password" required
                                            autocomplete="new-password" name="password"
                                            placeholder="Entrez votre mot de passe" />
                                    </div>

                                    <div class="space-y-1">
                                        <label for="tk-pages-sign-in-password" class="font-medium">
                                            Entrez à nouveau le mot de passe
                                        </label>
                                        <input
                                            class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                            type="password" id="tk-pages-sign-in-password" required
                                            autocomplete="current-password" name="password_confirmation"
                                            placeholder="Entrez votre mot de passe" />
                                    </div>

                                    <div>
                                        <button type="submit"
                                            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none w-full px-4 py-3 leading-6 rounded border-primary-700 bg-primary-700 text-white hover:text-white hover:bg-primary-800 hover:border-primary-800 focus:ring focus:ring-primary-500 focus:ring-opacity-50 active:bg-primary-700 active:border-primary-700">
                                            Créer un nouveau mot de passe
                                        </button>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                        <!-- END Sign In Content -->
                    </div>
                </div>
                <!-- END Sign In Section -->
            </div>
        </main>
        <!-- END Page Content -->
    </div>
</x-guest-layout>
