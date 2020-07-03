<html>
<head>
	<title>{{config('app.name')}} - @yield('title')</title>
	<link rel="icon" type="image/png" href="{{asset('images/favicon.ico')}}"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
	<link href="{{asset('css/style.css')}}" rel="stylesheet"/>

	@yield('stylesheet')

</head>
<body>

	<div class="container-fluid">
		<div class="row">
			@section('sidebar')
				<section class="col-3 px-1 px-md-3 lateral-nav">
					<img  class="img-fluid" src="{{asset('images/logo/logo.png')}}" alt="logo">

					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link {{(request()->is('mapa-incidentes') or request()->is('lista-incidentes')) ? 'active':''}}" id="nav-incidents" href="{{route('mapaIncidentes')}}" role="tab" aria-selected="true">Incidentes</a>
						<a class="nav-link {{(request()->is('usuarios')) ? 'active':''}}" id="nav-users" href="{{route('users')}}" role="tab" aria-selected="false">Usuarios</a>
{{--						<a class="nav-link {{(request()->is('analizar-incidentes')) ? 'active':''}}" id="nav-analyze-incidents" href="/analizar-incidentes" role="tab" aria-selected="false">Analizar incidentes</a>--}}
					</div>
				</section>
			@show

			<main class="offset-3 col-9 col-sm-7 col-lg-6 mt-2 mt-lg-4 col-xl-7 px-1 py-3 px-md-3 p-lg-4">
				@yield('content')
			</main>

			@section('top-right-header')
				<aside class="top-right-aside col-3 col-xl-2 p-2 pr-md-4 d-none d-sm-block">
					<div class="icon-group mt-2 d-flex justify-content-end">
						<a href="{{route('zonaPersonal')}}" id="user" class="icon my-2 my-lg-0">
							<img class="icon-img" src="{{asset('images/icons/admin.svg')}}">
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

	<script src="{{asset('js/bootstrap.min.js')}}"></script>
	<script src="{{asset('js/jquery-3.4.1.js')}}"></script>
	<script src="{{asset('js/bootstrap.js')}}"></script>
	@yield('scripts')
</body>
</html>
