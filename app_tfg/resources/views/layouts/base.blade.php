<html>
<head>
    <title>{{config('app.name')}} - @yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('css/style.css')}}" rel="stylesheet"/>

    @yield('stylesheet')

</head>
<body>

    <div class="container-fluid">
        <div class="row">
            @section('sidebar')
                <section class="col-3 px-1 px-md-3 lateral-nav">
                    <img class="img-fluid" src="{{asset('images/logo/logo.png')}}" alt="logo">

                    <div class="nav flex-column nav-pills" id="v-pills-tab" {{--role="tablist"--}} aria-orientation="vertical">
                        <a class="nav-link {{(request()->is('mapa-incidentes') or request()->is('lista-incidentes')) ? 'active':''}}" id="nav-incidents" href="{{route('mapaIncidentes')}}" role="tab" aria-selected="true">Incidentes</a>
                        <a class="nav-link {{(request()->is('contactos-favoritos')) ? 'active':''}}" id="nav-fav-contacts" href="{{route('contactosFavoritos')}}" role="tab" aria-selected="false">Contactos favoritos</a>
                        <a class="nav-link {{(request()->is('zonas-interes')) ? 'active':''}}" id="nav-areas-interest" href="{{route('zonasInteres')}}" role="tab" aria-selected="false">Zonas de interés</a>
                    </div>
                </section>
            @show

            <main class="offset-3 col-9 col-sm-7 col-lg-6 mt-2 mt-lg-4 px-1 py-3 px-md-3 p-lg-4">
                @yield('content')
            </main>

            @section('top-right-header')
                <aside class="top-right-aside col-2 offset-lg-1 p-0 pr-md-2 pr-xl-4 d-none d-sm-block">
                    <div class="icon-group mt-2 float-right">
                        <a href="#" id="lupa" class="icon my-2 my-lg-0">
                            <img class="icon-img" src="{{asset('images/icons/lupa.svg')}}">
                        </a>

                        <a tabindex="0" id="campana" class="btn icon my-2 my-lg-0" role="button" data-toggle="popover">
                            <img class="icon-img" src="{{asset('images/icons/campana.svg')}}">
                            @if($notifications->count() > 0)
                                <span id="notif-badge" class="badge badge-pill badge-danger">
                                    {{$notifications->count()}}
                                </span>
                            @endif
                        </a>

{{--                        <a href="#" id="campana" class="icon my-2 my-lg-0">--}}
{{--                            <img class="icon-img" src="{{asset('images/icons/campana.svg')}}">--}}
{{--                        </a>--}}
                        <a href="{{route('zonaPersonal')}}" id="user" class="icon my-2 my-lg-0">
                            <img class="icon-img" src="{{asset('images/icons/user.svg')}}">
                        </a>
                    </div>

                    <div class="user-logout float-right">
                        <p class="m-1">@yield('username')</p>
                        <a href="{{route('logout')}}">Cerrar sesión</a>
                    </div>

                    @yield('filter')
                </aside>
            @show
        </div>
    </div>

    <div id="popover-content" class="popover-content d-none">
        @foreach($notifications as $notification)
            @isset($notification->data['notification_type'])
                @switch($notification->data['notification_type'])
                    @case("befavcontact")
                        <div id="notification_{{$notification->id}}" class="notification_{{$notification->id}}">
                            <img class="float-left mr-1" src="{{asset('images/icons/nuevo-contacto.svg')}}" width="20px">
                            <span><b>
                                <abbr title="{{$notification->data['sender_email']}}">{{$notification->data['sender_name']}}</abbr> {{$notification->data['message']}}
                            </b></span>
                            <div class="text-center">
                                <button id="bfc-{{$notification->data['sender_id']}}-{{$notification->data['recipient_id']}}"
                                        class="btn btn-success mr-3">Aceptar</button>
                                <button id="notfc-{{$notification->data['sender_id']}}-{{$notification->data['recipient_id']}}"
                                        class="btn btn-danger ml-3">Rechazar</button>
                            </div>
                        </div>
                        @break

                    @case("interest_area_incident")
                        <div id="notification_{{$notification->id}}" class="notification_{{$notification->id}}">
                            <img class="float-left mr-1" src="{{asset('images/icons/alert.svg')}}" width="20px">
                            <span><b>{{$notification->data['message']}}</b></span>
                            <div class="text-center">
                                <button id="iai-{{$notification->id}}" class="btn btn-read-notification">
                                    Marcar como leída
                                </button>
                            </div>
                        </div>
                    @break
                @endswitch
            @endisset
        @endforeach
    </div>

    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/jquery-3.4.1.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/notifications.js')}}"></script>
    @yield('scripts')
</body>
</html>
