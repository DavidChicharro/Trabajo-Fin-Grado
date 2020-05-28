@extends('layouts.index-base')

@section('title', '| Incidente')

@section('stylesheet')
	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	      crossorigin=""/>
@endsection

@section('content')
<section class="logo col-12 text-center">
	<img class="img-fluid" src="{{asset('images/logo/logo.png')}}" alt="logo" width="300px">
</section>

<section class="col-12 offset-sm-1 col-sm-10 offset-md-2 col-md-8 offset-lg-3 col-lg-6 my-3">
	<article class="incident incident-detail px-4 py-2 mx-md-4">
		<h4 class="text-center">{{ucfirst($del)}}</h4>
		<p>{{$incident['nombre_lugar']}}<br>
			<small>@dateTimeFormat($incident['fecha_hora_incidente'])</small>
		</p>

		<p>{{$incident['descripcion_incidente']}}</p>
	</article>

	<article class="my-2 mx-md-4" id="mapid" style="height: 400px"></article>
</section>
<div>
	<input type="text" id="lat" value="{{$incident['latitud_incidente']}}" hidden>
	<input type="text" id="lng" value="{{$incident['longitud_incidente']}}" hidden>
</div>
@endsection

@section('scripts')
	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
	        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
	        crossorigin=""></script>

	<script src="{{asset('js/incidents/incident-detail.js')}}"></script>
@endsection
