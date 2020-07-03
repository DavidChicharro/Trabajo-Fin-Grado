@extends('layouts.admin-base')

@section('title', 'Administración')
@section('username', $username)
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

	<h2>Administración - Inicio</h2>
	<ul class="admin-list mt-4 pl-1">
		<li class="py-2">
			<a href="#" id="caduc" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar caducidad de incidentes</a>
		</li>
		<li class="py-2">
			<a href="#" id="cfav" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar número máximo de contactos favoritos</a>
		</li>
		<li class="py-2">
			<a href="#" id="zint" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar zonas de interés</a>
		</li>
		<li class="py-2">
			<a href="#" class="li-as-lk py-2">Dar de alta administrador</a>
		</li>
	</ul>

	<!-- Modal para ver y establecer parámetros de configuración -->
	<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header py-2">
					<h5 class="modal-title" id="config-title">Modal</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body container-fluid">
					<div class="row" id="content-modal-id">
					</div>
				</div>
				<div class="modal-footer py-2">
					<button id="save-config" type="button" class="btn modal-button" data-dismiss="modal">Aceptar</button>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	<script src="{{asset('js/admin.js')}}"></script>
@endsection
