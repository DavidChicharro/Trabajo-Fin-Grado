@extends('layouts.base')

@section('title', 'Mapa de incidentes')
@section('username', $username)
@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	      crossorigin=""/>
@endsection

@section('filter')
	<div class="filter float-right w-100 mt-2 pr-1 pr-md-0">
		<div>
			<div class="form-group">
				<select class="form-control selectpicker" name="tipos_incidentes[]" id="tipos-incid" title="Tipos incidentes" multiple data-live-search="true" data-selected-text-format="count > 3">
					@foreach($incidentTypes as $id => $incid)
						<option value="{{$id}}">{{ucfirst(strtolower($incid))}}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label for="desde">Desde</label>
				<input type="date" name="desde" class="form-control" id="date-from">
			</div>

			<div class="form-group">
				<label for="hasta">Hasta</label>
				<input type="date" name="hasta" class="form-control" id="date-to">
			</div>

			<button id="btn-filter-incident" class="btn form-button">Filtrar</button>
		</div>

		<a class="btn-add-incidcente mt-4" href="{{route('nuevoIncidente')}}">Añadir incidente</a>
	</div>
@endsection

@section('content')
	<h2 class="section-title pl-1 pl-sm-5 px-md-1">Mapa de incidentes</h2>

	@if(Session::has('message'))
		<div class="alert alert-primary alert-dismissible fade show" role="alert">
			{{Session::get('message')}}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	@endif

	<section class="main-content mx-1">
		<article class="applied-filter my-2 px-2 d-none"></article>

		<div id="mapid" style="height: 500px"></div>

		<div class="my-3">
			<a href="{{route('incidentesSubidos')}}">Mis publicaciones de incidentes</a>
			<a class="float-right" href="{{route('listaIncidentes')}}">Ver lista</a>
		</div>

	</section>

@endsection

@section('scripts')
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
	        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
	        crossorigin=""></script>

	<script src="{{asset('js/incidents/map-incidents.js')}}"></script>
@endsection
