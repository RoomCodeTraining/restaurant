a<div>
    @if(session()->has('success'))
        <x-alert title="Opération réussie" type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session()->has('error'))
        <x-alert title="Un erreur est survenue" type="danger">
            {{ session('error') }}
        </x-alert>
    @endif

    @if(session()->has('warning'))
        <x-alert title="Attention !" type="warning">
            {{ session('warning') }}
        </x-alert>
    @endif

    @if(session()->has('info'))
        <x-alert title="Info" type="info">
            {{ session('info') }}
        </x-alert>
    @endif
</div>