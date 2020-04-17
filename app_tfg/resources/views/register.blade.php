@extends('layouts.index-base')
@section('title', ' - Registro')

@section('content')

	<a href="/" class="mx-auto">
		<img class="img-fluid px-4" src="{{asset('images/logo/logo.png')}}" alt="logo" width="400px">
	</a>

	<div class="row col-12 mx-auto">
		<section class="form-section mx-auto">
			<h2 class="text-center">Registro</h2>

			<form method="post" action="{{ Request::url() }}">
				@csrf
				<div class="form-group">
					<label class="required-input" for="email">E-mail</label>
					<input type="email" name="email" class="form-control" required>
				</div>

				<div class="form-group">
					<label class="required-input" for="password">Contraseña</label>
					<input type="password" name="password" class="form-control" required>
				</div>

				<div id="passMatch" class="form-group d-none">
					<span class="text-danger">¡Las contraseñas no coinciden!</span>
				</div>

				<div class="form-group">
					<label class="required-input" for="conf_password">Confirmar contraseña</label>
					<input type="password" name="conf_password" class="form-control" required>
				</div>

				<input type="submit" value="Siguiente" class="form-button" disabled>
			</form>

			<div class="text-center">
				<img class="img-fluid" src="{{asset('images/icons/reg-progess-1.svg')}}">
			</div>

			<article class="reg-log text-center p-3 mt-2">
				<span>¿Ya tienes cuenta?</span><br>
				<a href="/">Inicia sesión</a>
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
	<script src="{{asset('js/register.js')}}"></script>
@endsection