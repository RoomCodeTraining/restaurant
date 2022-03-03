@extends("totem::layout")
@section('page-title')
    @parent
    - Tâches
@stop
@section('title')
    <div class="uk-flex uk-flex-between uk-flex-middle">
        <h4 class="uk-card-title uk-margin-remove">Liste des tâches</h4>
      
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary hidden md:flex font-bold">
                Retour
            </a>
    
        {!! Form::open([
    'id' => 'totem__search__form',
    'url' => Request::fullUrl(),
    'method' => 'GET',
    'class' => 'uk-display-inline uk-search uk-search-default',
]) !!}
        <span uk-search-icon></span>
        {!! Form::text('q', request('q'), ['class' => 'uk-search-input', 'placeholder' => 'Rechercher...']) !!}
        {!! Form::close() !!}
    </div>
@stop
@section('main-panel-content')
    <table class="uk-table uk-table-responsive" cellpadding="0" cellspacing="0" class="mb1">
        <thead>
            <tr>
                <th>{!! Html::columnSort('Description', 'description') !!}</th>
                <th>{!! Html::columnSort("Durée d'exécution moyenne", 'average_runtime') !!}</th>
                <th>{!! Html::columnSort('Dernière exécution', 'last_ran_at') !!}</th>
                <th>Prochaine exécution</th>
                <th class="uk-text-center">Executer</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr is="task-row" :data-task="{{ $task }}" showHref="{{ route('totem.task.view', $task) }}"
                    executeHref="{{ route('totem.task.execute', $task) }}">
                </tr>
            @empty
                <tr>
                    <td class="uk-text-center" colspan="5">
                        <img class="uk-svg" width="50" height="50" src="{{ asset('/vendor/totem/img/funnel.svg') }}">
                        <p>Aucune tache n'a été planifiée</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop
@section('main-panel-footer')
    <div class="uk-flex uk-flex-between">
        <span>
            <a class="uk-icon-button uk-button-primary uk-hidden@m" uk-icon="icon: plus"
                href="{{ route('totem.task.create') }}"></a>
            <a class="uk-button uk-button-primary uk-button-small uk-visible@m" href="{{ route('totem.task.create') }}">Nouvelle tâche </a>
        </span>
    </div>
    {{ $tasks->links('totem::partials.pagination', ['params' => '&' . http_build_query(array_filter(request()->except('page')))]) }}
@stop
