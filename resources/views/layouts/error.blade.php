<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <title>{{ config('app.name', 'Ciprel Cantine') }}</title>
</head>

<body class="font-sans antialiased bg-primary">

    <div class="flex items-center justify-center w-screen h-screen">
        <div class="px-4 lg:py-12">
            <div class="lg:gap-4 lg:flex">
                <div class="flex flex-col items-center justify-center md:py-24 lg:py-32">
                    @yield('content')
                    <a href="/" class="px-6 py-2 text-sm font-semibold text-blue-800 bg-primary-700">Accueil</a>
                </div>
                <div class="mt-4">
                    @yield('image')
                </div>
            </div>
        </div>
    </div>
</body>

</html>
