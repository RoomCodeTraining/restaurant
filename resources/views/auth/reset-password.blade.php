<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <div class="flex font-bold justify-center mb-6 mt-2">
                <img class="h-auto w-1/3" src="{{ asset('images/logo-ciprel-vf.png') }}">
            </div>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="w-full text-center px-2">
                <!-- Email Address -->
                <div class="w-full">
                    <div class="flex items-center">
                        <i class="ml-3 fill-current text-gray-400 text-xs z-10 fas fa-envelope-open-text"></i>
                        <input type="email" id="email" placeholder="Entrez votre email" name="email"
                            :value="old('email', $request->email)"
                            class="-mx-6 px-8 w-full rounded py-2 bg-opacity-25 text-gray-600 focus:outline-none bg-gray-100"
                            required autofocus />
                    </div>
                </div>

                <!-- Password -->
                <div class="w-full mt-4">
                    <div class="flex items-center">
                        <i class='ml-3 fill-current text-gray-400 text-xs z-10 fas fa-lock'></i>
                        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe"
                            class="-mx-6 px-8 w-full rounded py-2 bg-opacity-25 text-gray-600 focus:outline-none bg-gray-100"
                            required autocomplete="current-password" />
                    </div>
                </div>
                <!-- Confirm Password -->
                <div class="w-full mt-4">
                    <div class="flex items-center">
                        <i class='ml-3 fill-current text-gray-400 text-xs z-10 fas fa-lock'></i>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Entrez à nouveau votre mot de passe"
                            class="-mx-6 px-8 w-full rounded py-2 bg-opacity-25 text-gray-600 focus:outline-none bg-gray-100"
                            required autocomplete="current-password" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="w-full py-3 font-medium bg-primary-900 text-gray-100  focus:outline-none">
                    Réinitialiser le mot de passe
                </button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
