@extends('layouts.base')

@section('title', 'Zonas de interés')
@section('username',$username)
@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	      crossorigin=""/>
@endsection

@section('content')
	<h2>Zonas de interés</h2>

	@if(Session::has('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{Session::get('error')}}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	@endif

	<section class="main-content mx-1 my-4 row">
		@if($numInterestAreas > 0)
			<div id="mapid" class="col-8" style="height: 500px"></div>

		@else
			<article class="no-interest-area col-8 pt-3 text-center">
				<p>No tienes ninguna zona de interés</p>
			</article>
		@endif

		<article class="interest-areas-menu col-4 p-2">
			<a class="btn-add-contacto mt-4" href="{{route('nuevaZona')}}">Añadir zona de interés</a>

			<div>
				<div class="form-group">
					<select class="form-control selectpicker my-4" name="zonas_interes[]" id="zonas_interes"
					        title="Zonas de interes" data-live-search="true">
					</select>
				</div>

				<img id="remove-interest-area" class="img-fluid img-as-lk ml-2 d-none"
				     src="{{asset('images/icons/papelera.svg')}}" width="30px">

			</div>
		</article>
	</section>

@endsection

@section('scripts')
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
	        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
	        crossorigin=""></script>

	<script src="{{asset('js/interest_areas/areas.js')}}"></script>
@endsection