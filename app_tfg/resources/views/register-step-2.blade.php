@extends('layouts.index-base')

@section('title', ' - Registro')

@section('content')

	<a href="/" class="mx-auto">
		<img class="img-fluid px-4" src="{{asset('images/logo/logo.png')}}" alt="logo" width="400px">
	</a>

	<div class="row col-12 mx-auto">
		<section class="form-section mx-auto">
			<h2 class="text-center">Registro</h2>

			<form method="post" action="{{ action('UsersController@store') }}">
				@csrf
				<input type="hidden" name="email" value="{{$datos['email']}}" required>
				<input type="hidden" name="password" value="{{$datos['password']}}" required>

				<div class="form-group">
					<label class="required-input" for="nombre">Nombre</label>
					<input type="text" name="nombre" class="form-control" required>
				</div>

				<div class="form-group">
					<label class="required-input" for="apellidos">Apellidos</label>
					<input type="text" name="apellidos" class="form-control" required>
				</div>

				<div class="form-group">
					<label class="required-input" for="dni">D.N.I.</label>
					<input type="text" name="dni" class="form-control" required>
					<div class="invalid-feedback" id="invalid-dni">El D.N.I. es inválido</div>
				</div>

				<div class="form-group">
					<label class="required-input" for="fecha_nacimiento">Fecha de nacimiento</label>
					<input type="date" name="fecha_nacimiento" class="form-control" required>
				</div>

				<div class="form-group">
					<label class="required-input" for="telefono">Número de teléfono móvil</label>
					<input type="tel" name="telefono" class="form-control" required>
				</div>

				<input type="submit" value="Registrarse" class="form-button" disabled>
			</form>

			<div class="text-center">
				<img class="img-fluid" src="{{asset('images/icons/reg-progess-2.svg')}}">
			</div>

			<article class="reg-log text-center p-3 mt-2">
				<span>¿Ya tienes cuenta?</span><br>
				<a href="{{route('index')}}">Inicia sesión</a>
			</article>
		</section>

		@if($errors->any())
			@foreach($errors->all() as $error)
				<script>alert('{{$error}}')</script>
			@endforeach
		@endif
	</div>

@endsection


@section('scripts')
	<script src="{{asset('js/register-stp2.js')}}"></script>
@endsection