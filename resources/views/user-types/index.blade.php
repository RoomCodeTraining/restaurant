<x-app-layout>
    <x-section-header title="Types d'utilisateurs">
        @can('manage', App\Models\UserType::class)
            <x-slot name="actions">
                <a href="{{ route('userTypes.create') }}" class="btn btn-sm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block font-bold w-4 h-4 mr-1 stroke-current"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 12 12">
                        <g fill="none">
                            <path
                                d="M5.898 2.007L6 2a.75.75 0 0 1 .743.648l.007.102v2.499l2.5.001a.75.75 0 0 1 .743.648L10 6a.75.75 0 0 1-.648.743l-.102.007l-2.5-.001V9.25a.75.75 0 0 1-.648.743L6 10a.75.75 0 0 1-.743-.648L5.25 9.25V6.749l-2.5.001a.75.75 0 0 1-.743-.648L2 6a.75.75 0 0 1 .648-.743l.102-.007l2.5-.001V2.75a.75.75 0 0 1 .648-.743L6 2l-.102.007z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                    Nouveau
                </a>
            </x-slot>
        @endcan
    </x-section-header>

    <div class="bg-white px-6 py-4 rounded-md shadow-lg">
        <livewire:user-types.user-types-table />
    </div>
</x-app-layout>
