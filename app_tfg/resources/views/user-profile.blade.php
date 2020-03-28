@extends('layouts.base')

@section('title', 'Zona personal')
@section('username',$username)

@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
@endsection


@section('content')
	<h2>Zona personal</h2>
	<section class="main-content pl-3 pt-4">
		<article class="my-2">
			<h5 class="p-1">Configuración</h5>
			<ul class="admin-list pl-3">
				<li class="py-1">
					<a href="#" id="panic-action" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configUser">Establecer acción de pánico</a>
				</li>
				<li class="py-1">
					<a href="#" id="secret-pin" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configUser">Establecer PIN secreto</a>
				</li>
			</ul>
		</article>

		<article class="my-4">
			<h5 class="p-1">Nombre</h5>
			<form class="pl-3 pr-5 w-50" method="post" action="{{ Request::url() }}">
				@csrf
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="{{ $user['nombre'] }}" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="apellidos">Apellidos</label>
					<input type="text" name="apellidos" value="{{ $user['apellidos'] }}" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" name="email" value="{{ $user['email'] }}" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="dni">D.N.I. / N.I.E.</label>
					<input type="text" name="dni_" value="{{ $user['dni'] }}" class="form-control" readonly>
				</div>

				<div class="form-group">
					<label for="fecha_nacimiento">Fecha de nacimiento</label>
					<input type="date" name="fecha_nacimiento" value="@dateInputFormat($user['fecha_nacimiento'])" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="telefono">Número de teléfono móvil</label>
					<input type="tel" name="telefono" value="{{ $user['telefono'] }}" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="telefono_fijo">Número de teléfono fijo</label>
					<input type="tel" name="telefono_fijo" value="{{ $user['telefono_fijo'] }}" class="form-control">
				</div>

				<input type="submit" value="Guardar cambios" class="form-button" name="formData">
			</form>
		</article>

		<div>
			@if($errors->any())
				@foreach($errors->all() as $error)
					<script>alert('{{$error}}')</script>
				@endforeach
			@endif
		</div>

		<article class="my-4">
			<h5 class="p-1">Seguridad</h5>
			<form class="pl-3 pr-5 w-50" method="post" action="{{ Request::url() }}">
				@csrf
				<div class="form-group">
					<label for="password">Contraseña actual</label>
					<input type="password" name="password" class="form-control">
				</div>

				<div class="form-group">
					<label for="new_password">Nueva contraseña</label>
					<input type="password" name="new_password" class="form-control">
				</div>

				<div class="form-group">
					<label for="conf_password">Confirmar contraseña</label>
					<input type="password" name="conf_password" class="form-control">
				</div>

				<input type="submit" value="Cambiar contraseña" class="form-button" name="formPass" disabled>
			</form>
		</article>

		<article class="my-4">
			<h5 class="p-1">Cuenta</h5>
			<a href="#" class="text-danger px-3">Cerrar cuenta</a>
		</article>
	</section>

@endsection


@section('scripts')
	<script src="{{asset('js/user-profile.js')}}"></script>
@endsection