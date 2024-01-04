        <nav id="page-sidebar"
            class="flex flex-col fixed top-0 left-0 bottom-0 w-full lg:w-72 h-full bg-gray-900 border-r border-gray-100 transform transition-transform duration-100 ease-out z-20"
            x-bind:class="{
                '-translate-x-full': !mobileSidebarOpen,
                'translate-x-0': mobileSidebarOpen,
                'lg:-translate-x-full': !desktopSidebarOpen,
                'lg:translate-x-0': desktopSidebarOpen,
            }"
            aria-label="Sidebar">
            <!-- Sidebar Header -->
            <div class="h-16 flex-none flex items-center justify-between lg:justify-center px-4 w-full">
                <!-- Brand -->
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center font-bold text-lg tracking-wide text-gray-600 hover:text-gray-500">
                    <x-application-logo class="h-12" />
                </a>
                <div class="lg:hidden">
                    <button type="button"
                        class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-5 text-sm rounded border-transparent text-red-600 hover:text-red-400 focus:ring focus:ring-red-500 focus:ring-opacity-50 active:text-red-600"
                        x-on:click="mobileSidebarOpen = false">
                        <svg class="hi-solid hi-x inline-block w-4 h-4 -mx-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="overflow-y-auto customScroll">
                <div class="p-4 w-full">
                    <nav class="space-y-1">
                        <x-nav-link href="{{ route('dashboard') }}" icon="home" :active="request()->routeIs('dashboard')">
                            Tableau de bord
                        </x-nav-link>
                        <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                            Resto
                        </div>
                        <x-nav-link href="{{ route('reporting.check.breakfast') }}" icon="chart" :active="request()->routeIs('reporting.check.breakfast')">
                            Plat
                        </x-nav-link>
                        <x-nav-link href="{{ route('reporting.check.breakfast') }}" icon="chart" :active="request()->routeIs('reporting.check.breakfast')">
                            Menu
                        </x-nav-link>

                        <div class="px-3 pt-5 pb-2 text-xs font-medium uppercase tracking-wider text-gray-500">
                            Reporting
                        </div>
                        <x-nav-link href="{{ route('reporting.check.breakfast') }}" icon="chart" :active="request()->routeIs('reporting.check.breakfast')">
                            Commandes
                        </x-nav-link>
                    </nav>
                </div>
            </div>
        </nav>
