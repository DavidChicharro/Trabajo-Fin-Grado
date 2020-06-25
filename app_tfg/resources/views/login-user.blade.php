@extends('layouts.index-base')

@section('content')

	<img class="img-fluid px-4" src="{{asset('images/logo/logo.png')}}" alt="logo" width="350px">

	<div class="main-content row w-100 mx-0">
		<section class="d-none d-sm-block col-7 p-5 text-center">
			<ul class="text-left mb-5">
				<li class="index-li">
					Aplicación web de seguridad ciudadana
				</li>
				<li class="index-li">
					Mantente informado de los incidentes que ocurren en tu ciudad
				</li>
				<li class="index-li">
					Guarda zonas de interés para ser alertado de incidentes
				</li>
				<li class="index-li">
					Añade a otros usuarios como contactos
				</li>
			</ul>

			<a class="download-app text-center p-3" href="{{route('downloadApp')}}" target="_blank">
				Descarga la app
			</a>
			<br><br>
			<a class="rrss-link text-center p-3" href="https://twitter.com/kifungo_app" target="_blank">
				<img class="img-fluid" src="{{asset('images/icons/twitter_logo.svg')}}" width="40px">
				<span>Síguenos en Twitter</span>
			</a>
		</section>

		<section class="col-12 col-sm-5 offset-lg-1 col-lg-3 px-3">
			@include('layouts.login')

			<article class="reg-log text-center p-3 mt-2">
				<span>¿No tienes cuenta?</span><br>
				<a href="{{route('registro')}}">Regístrate</a>
			</article>
		</section>
	</div>

@endsection

@section('scripts')
	<script src="{{asset('js/login.js')}}"></script>
@endsection
