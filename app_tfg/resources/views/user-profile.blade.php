@extends('layouts.base')

@section('title', 'Zona personal')
@section('username',$username)


@section('content')
	<h2>Zona personal</h2>
	<section class="main-content pl-3 pt-4">
		<article class="my-2">
			<h5 class="p-0">Configuración</h5>
			<ul class="admin-list pl-3">
				<li class="py-1">
					<a href="#" id="panic-action" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configUser">Establecer acción de pánico</a>
				</li>
				<li class="py-1">
					<a href="#" id="secret-pin" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configUser">Establecer PIN secreto</a>
				</li>
			</ul>
		</article>

		<article class="my-2">
			<h5 class="p-1">Datos personales</h5>
		</article>

		<article class="my-2">
			<h5 class="p-1">Seguridad</h5>
		</article>

		<article class="my-2">
			<h5 class="p-1">Cuenta</h5>
			<a href="#">Cerrar cuenta</a>
		</article>
	</section>

@endsection