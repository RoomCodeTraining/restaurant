<aside class="uk-width-1-6@l uk-margin-bottom">
    <div class="uk-text-center">
        <x-application-logo class="h-12" />
        <div class="uk-text-large">Planificateur de tâches</div>
    </div>
    <hr>
    <ul class="uk-nav uk-nav-default">
        <li class="{{ Str::contains(url()->current(), 'tasks') ? 'uk-active' : '' }}">
            <a href="{{route('totem.tasks.all')}}" class="uk-flex uk-flex-middle">
                <span uk-icon="icon: clock; ratio: 1" class="uk-visible@m uk-margin-small-right"></span>
                <span class="uk-vertical-align-middle">Tâches</span>
            </a>
        </li>
    </ul>
    <hr>
</aside>
