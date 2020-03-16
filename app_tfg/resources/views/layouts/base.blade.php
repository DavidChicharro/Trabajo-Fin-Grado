<html>
<head>
    <title>{{config('app.name')}} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('css/style.css')}}" rel="stylesheet"/>

    <link href="{{asset('js/bootstrap.min.js')}}"/>
    <link href="{{asset('js/jquery-3.4.1.js')}}"/>
    @yield('stylesheet')

    <style>
        .icon-lupa {
            src: url({{asset('images/icons/lupa.svg')}});
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            @section('sidebar')
                <section class="col-3">
                    <img  class="img-fluid" src="{{asset('images/logo/logo.png')}}">
                </section>
            @show

            <main class="col-8 col-lg-7 mt-2 mt-lg-4 p-3 p-lg-4">
                @yield('content')
            </main>

            @section('top-right-header')
                <aside class="top-right-aside col-1 col-lg-2 p-0 p-md-2 d-none d-sm-block">
                    <div class="icon-group mt-2 float-right">
                        <a href="/" id="lupa" class="icon">
                            <img class="icon-img" src="{{asset('images/icons/lupa.svg')}}">
                        </a>
                        <a href="/" id="campana" class="icon">
                            <img class="icon-img" src="{{asset('images/icons/campana.svg')}}">
                        </a>
                        <a href="/" id="user" class="icon">
                            <img class="icon-img" src="{{asset('images/icons/user.svg')}}">
                        </a>
                    </div>

                    <div class="user-logout float-right">
                        <p class="text-right m-1">@yield('username')</p>
                        <a href="/logout">Cerrar sesión</a>
                    </div>
                </aside>
            @show
        </div>
    </div>
</body>
</html>