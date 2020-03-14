<html>
    <head>
        <title>Kifungo - @yield('title')</title>
    </head>
    <body>
        @isset($session)
            @section('sidebar')
                This is the master sidebar.
            @show
            
            <div class="container">
                @yield('content')
            </div>
        @endisset

        @if(is_null($session))
            <p>No hay sesión</p>
        @endif
    </body>
</html>