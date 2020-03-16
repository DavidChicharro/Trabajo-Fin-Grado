<html>
<head>
    <title>{{config('app.name')}} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('css/style.css')}}" rel="stylesheet"/>

    <link href="{{asset('js/bootstrap.min.js')}}"/>
    <link href="{{asset('js/jquery-3.4.1.js')}}"/>
    @yield('stylesheet')

</head>
<body>

    <div class="container-fluid">
        <div class="row">
            @section('sidebar')
                <section class="col-3 lateral-nav">
                    <img  class="img-fluid" src="{{asset('images/logo/logo.png')}}">

                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link {{(request()->is('mapa-incidentes') or request()->is('lista-incidentes')) ? 'active':''}}" id="nav-incidents" data-toggle="pill" href="/mapa-incidentes" role="tab" aria-controls="v-pills-home" aria-selected="true">Incidentes</a>
                        <a class="nav-link {{(request()->is('contactos-favoritos')) ? 'active':''}}" id="nav-fav-contacts" data-toggle="pill" href="/contactos-favoritos" role="tab" aria-controls="v-pills-profile" aria-selected="false">Contactos favoritos</a>
                        <a class="nav-link {{(request()->is('zonas-interes')) ? 'active':''}}" id="nav-areas-interest" data-toggle="pill" href="/zonas-interes" role="tab" aria-controls="v-pills-messages" aria-selected="false">Zonas de interés</a>
{{--                        <a class="nav-link {{(request()->is('settings')) ? 'active':''}}" id="nav-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>--}}
                    </div>
{{--                    <div class="icons-menu icon-group">--}}
{{--                        <a class="icon" href="/mapa-incidentes">--}}
{{--                            <img class="icon-img" src="{{asset('images/icons/alert.svg')}}"/>--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </section>
            @show

            <main class="offset-3 col-9 col-sm-8 col-lg-7 mt-2 mt-lg-4 p-3 p-lg-4">
                @yield('content')
            </main>

            @section('top-right-header')
                <aside class="top-right-aside col-1 col-lg-2 p-0 p-md-2 pr-xl-4 d-none d-sm-block">
                    <div class="icon-group mt-2 float-right">
                        <a href="/" id="lupa" class="icon my-2 my-lg-0">
                            <img class="icon-img" src="{{asset('images/icons/lupa.svg')}}">
                        </a>
                        <a href="/" id="campana" class="icon my-2 my-lg-0">
                            <img class="icon-img" src="{{asset('images/icons/campana.svg')}}">
                        </a>
                        <a href="/" id="user" class="icon my-2 my-lg-0">
                            <img class="icon-img" src="{{asset('images/icons/user.svg')}}">
                        </a>
                    </div>

                    <div class="user-logout float-right">
                        <p class="m-1">@yield('username')</p>
                        <a href="/logout">Cerrar sesión</a>
                    </div>
                </aside>
            @show
        </div>
    </div>
</body>
</html>