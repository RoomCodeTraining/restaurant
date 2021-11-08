a<div>
    @if (session()->has('success'))
        <div class="alert alert-info">
            <div class="flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="w-6 h-6 mx-2 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <label>{{ session('success') }}</label>
            </div>
        </div>
        <x-alert title="Opération réussie" type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session()->has('error'))
        <x-alert title="Un erreur est survenue" type="danger">
            {{ session('error') }}
        </x-alert>
    @endif

    @if (session()->has('warning'))
        <x-alert title="Attention !" type="warning">
            {{ session('warning') }}
        </x-alert>
    @endif

    @if (session()->has('info'))
        <x-alert title="Info" type="info">
            {{ session('info') }}
        </x-alert>
    @endif
</div>
