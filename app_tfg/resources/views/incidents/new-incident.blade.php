@extends('layouts.base')

@section('title', 'Añadir incidente')
@section('username',$username)
@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	      crossorigin=""/>

	<link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
@endsection

@section('content')
	<h2 class="section-title pl-1 pl-sm-5 px-md-1">Dar de alta incidente</h2>
	<section class="main-content mx-1">
		<form class="px-2" method="post" action="{{ Request::url() }}">
			@csrf
			<div class="form-row">
				<div class="form-group col-sm-5">
					<label for="categ-delito">Tipo de delito</label>
					<select class="form-control selectpicker" id="categ-delito" title="Categoría delito" multiple data-live-search="true" data-selected-text-format="count > 3">
						@foreach($delitos as $del)
							<option value="{{$del}}">{{ucfirst(strtolower($del))}}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group col-sm-1 p-0 pt-sm-4">
					<span id="search-delitos" class="sp-as-lk">❯❯</span>
					<span class="d-sm-none ml-3 text-muted">Clica en las flechas para buscar</span>
				</div>

				<div class="form-group col-sm-6" id="div-delito">
					<label for="delito">Delito</label>
					<select class="form-control selectpicker" name="delito" id="delito" title="Delito" data-live-search="true" required>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-sm-6">
					<label for="fecha_incidente">Fecha</label>
					<div class="input-group">
						<input type="date" name="fecha_incidente" class="form-control" required>
					</div>
				</div>

				<div class="form-group col-sm-6">
					<label for="hora_incidente">Hora</label>
					<div class="input-group">
						<input type="time" name="hora_incidente" class="form-control" required>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="lugar">Lugar</label>
				<input type="text" name="lugar" id="lugar" class="form-control" required hidden>
				<input type="text" name="nombre_lugar" id="nombre_lugar" class="form-control" hidden>
				<div class="map-cursor-pointer my-2" id="mapid" style="height: 400px"></div>
			</div>

			<div class="form-group">
				<label for="descripcion">Descripción</label>
				<textarea name="descripcion_incidente" id="descripcion" class="form-control" rows="4" required></textarea>
			</div>

			<div class="form-group">
				<label class="d-block" for="agravantes">Agravantes</label>
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="agravantes[]" type="checkbox" id="agr-disfraz" value="1">
					<label class="form-check-label" for="agr-disfraz">Disfraz</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="agravantes[]" type="checkbox" id="agr-abuso" value="2">
					<label class="form-check-label" for="agr-abuso">Abuso de superioridad</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="agravantes[]" type="checkbox" id="agr-sufimiento" value="3">
					<label class="form-check-label" for="agr-sufimiento">Sufrimiento inhumano</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="agravantes[]" type="checkbox" id="agr-discriminacion" value="4">
					<label class="form-check-label" for="agr-discriminacion">
						Racismo, discriminación, homofobia, machismo, ...
					</label>
				</div>
			</div>

			<div class="form-group">
				<label class="d-block">He sido</label>
				<div class="form-check">
					<input class="form-check-input" name="afectado_testigo" type="radio" id="radio-testigo" value="1" checked required>
					<label class="form-check-label" for="radio-testigo">Testigo</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" name="afectado_testigo" type="radio" id="radio-afectado" value="0">
					<label class="form-check-label" for="radio-afectado">Afectado</label>
				</div>
			</div>


			<input type="submit" value="Añadir incidente" class="form-button col-md-6" name="formData">
		</form>
	</section>

@endsection

@section('scripts')
	<!-- Leaflet JS -->
	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
	        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
	        crossorigin=""></script>
	<!-- Leaflet JS GeoWeb for searching places -->
	<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
	<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>

	<script src="{{asset('js/incidents/new-incident.js')}}"></script>

	<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
	<!-- Latest compiled and minified JavaScript translation files -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-es_ES.min.js"></script>
@endsection
