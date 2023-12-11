<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel='icon' href='{{ asset('images/logo-ciprel.png') }}' type='image/png' />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .customScroll::-webkit-scrollbar {
            width: 6px;
        }

        .customScroll::-webkit-scrollbar-track {
            background: inherit;
        }

        .customScroll::-webkit-scrollbar-thumb {
            background-color: #f07d00;
            /* color of the scroll thumb */
            border-radius: 20px;
            /* roundness of the scroll thumb */
            border: 3px solid #f07d00;
            /* creates padding around scroll thumb */
        }
    </style>
    @filamentStyles
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    @stack('styles')

    @stack('scripts')
    <title>{{ config('app.name', 'Ciprel Cantine') }}</title>
</head>

<body class="font-sans antialiased">
    <div @keydown.escape="showModal = false" x-cloak x-data="{ userDropdownOpen: false, mobileSidebarOpen: false, desktopSidebarOpen: true, 'showModal': false }"
        class="flex flex-col mx-auto w-full min-h-screen bg-stone-100"
        x-bind:class="{
            'lg:pl-72': desktopSidebarOpen
        }">
        @include('include.navigation')
        @include('include.header')
        <main id="page-content" class="flex flex-auto flex-col bg-stone-100 max-w-full pt-16">
            <!-- Page Section -->
            <div class="max-w-10xl mx-auto p-4 lg:p-8 w-full">
                {{-- @include('partials.flasher') --}}
                {{ $slot }}
            </div>
            <!-- END Page Section -->
        </main>
    </div>
    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
    @yield('js')
</body>

</html>
