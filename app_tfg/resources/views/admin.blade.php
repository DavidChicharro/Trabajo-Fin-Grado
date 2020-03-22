@extends('layouts.admin-base')

@section('title', 'Administración')
@section('username',$username)
@section('stylesheet')
	<link href="{{asset('css/login.css')}}" rel="stylesheet"/>
@endsection

@section('content')

	@isset($session)
		<div class="container">
			@if(Session::has('message'))
				{{--Inicio de sesión correcto--}}
				<div>
					<p>{{Session::get('message')}}</p>
				</div>
			@endif

		</div>
	@endisset
{{--	<button type="button" data-toggle="modal" data-target="#caducidadModal">Configurar caducidad de incidentes</button>--}}

	<h2>Administración - Inicio</h2>
	<ul class="admin-list mt-4 pl-1">
		<li class="py-2">
			<a href="#" class="li-as-lk py-2" data-toggle="modal" data-target="#caducidadModal">Configurar caducidad de incidentes</a>
		</li>
		<li class="py-2">
			<a href="#" class="li-as-lk py-2">Configurar número máximo de contactos favoritos</a>
		</li>
		<li class="py-2">
			<a href="#" class="li-as-lk py-2">Configurar zonas de interés</a>
		</li>
		<li class="py-2">
			<a href="#" class="li-as-lk py-2">Dar de alta administrador</a>
		</li>
	</ul>

	<!-- Modal caducidad incidente -->
	<div class="modal fade" id="caducidadModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Caducidad de incidentes</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<span>Radio</span>
					<input type="number">
					<span>metros</span>

					<span>Tiempo</span>
					<input type="number">
					<span>meses</span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')

{{--	<script>--}}
{{--		$('#configCaducidad').modal('show');--}}
{{--	</script>--}}
@endsection
