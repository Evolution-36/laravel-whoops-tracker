<ul id="slide-out" class="side-nav fixed">
    <li>
        <div class="user-view">
            <div class="background">
                <img src="/images/sunset.jpeg">
            </div>
            <span class="white-text hide-on-med-and-down header">{{ config('app.name', 'Evolution36') }}</span>
            <span class="white-text name">Whoops Tracker</span>
            <span class="white-text email">Dashboard</span>
        </div>
    </li>
    <li><a href="{{ action('WhoopsController@index') }}"><i class="material-icons">dashboard</i>Dashboard</a>
    </li>
</ul>

<div class="navbar-fixed hide-on-large-only">
    <nav>
        <div class="nav-wrapper">
            <a class="brand-logo" href="{{ url('/') }}">
                <span id="app-name">{{ config('app.name', 'Evolution36') }}</span>
            </a>
            <a href="#" data-activates="slide-out" class="button-collapse"><i
                    class="material-icons">menu</i></a>
        </div>
    </nav>
</div>


@push('js')
    <script>
        $(function () {
            $(".button-collapse").sideNav();
        });
    </script>
@endpush
