@extends('layouts.base')

@section('title', 'Añadir zona de interés')
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
	<h2>Añadir zona de interés</h2>
	<section class="main-content mx-1">
		<div id="mapid" class="map-cursor-pointer my-4" style="height: 420px"></div>
		<form class="px-3 pr-5" method="post" action="{{ Request::url() }}">
			@csrf

			<div class="form-group">
				<input type="text" name="lat_zona_int" id="lat_zona_int" class="form-control" hidden>
				<input type="text" name="long_zona_int" id="long_zona_int" class="form-control" hidden>
				<input type="text" name="nombre_lugar" id="nombre_lugar" class="form-control" hidden>
				<div id="slider-radio" hidden>
					<input type="range" name="radio_zona_int" id="radio_zona_int" class="form-control"
					       min="{{$config['radio_min']}}" max="{{$config['radio_max']}}"
					       value="600" step="50">
					<span id="radio-value"></span>
				</div>

			</div>

			<input type="submit" value="Añadir zona de interés" class="form-button col-md-5" disabled>
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

	<script src="{{asset('js/interest_areas/new-area.js')}}"></script>

	<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
	<!-- Latest compiled and minified JavaScript translation files -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-es_ES.min.js"></script>

@endsection