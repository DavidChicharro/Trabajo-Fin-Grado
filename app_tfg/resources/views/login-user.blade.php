@extends('layouts.index-base')

@section('content')

	<img class="img-fluid px-4" src="{{asset('images/logo/logo.png')}}" alt="logo" width="350px">

	<div class="main-content row col-12">
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

			<a class="download-app text-center p-3" href="#">Descarga la app</a>
		</section>

		<section class="col-12 col-sm-5 offset-lg-1 col-lg-3 px-5 px-sm-0">
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
