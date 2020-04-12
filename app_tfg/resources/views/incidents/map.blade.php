@extends('layouts.base')

@section('title', 'Mapa de incidentes')
@section('username',$username)
@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	      crossorigin=""/>

{{--	<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />--}}

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

		<a class="btn-add-incidcente mt-4" href="/nuevo-incidente">Añadir incidente</a>
	</div>
@endsection

@section('content')
	<h2>Mapa de incidentes</h2>
	<section class="main-content mx-1">
		<div id="mapid" style="height: 500px"></div>

		<div class="my-3">
			<a href="/mis-publicaciones-incidentes">Mis publicaciones de incidentes</a>
			<a class="float-right" href="/lista-incidentes">Ver lista</a>
		</div>

	</section>

@endsection

@section('scripts')
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
	        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
	        crossorigin=""></script>

	<script>
		function getIncidents(bounds, delitTypes=[], dateFrom="", dateTo="") {
		    let mapLimits = bounds.split(',');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/get_map_incidents',
                data: {
                    'mapLimits': mapLimits,
	                'delitTypes': delitTypes,
	                'dateFrom': dateFrom,
	                'dateTo': dateTo
                },
                type: 'post',
                success: function (response) {
                    // console.log(response);
                    let result = JSON.parse(response);
                    $.each(result, function(index, value){
                        L.marker([value.latitud, value.longitud])
	                        .bindPopup('<b>'+value.incidente+'</b>  -  '+
		                        value.fecha_hora+'.<br>'+value.nombre_lugar
		                        +'<br>'+value.descripcion)
	                        .addTo(incidentsLayerGroup);
                    });
                },
                statusCode: {
                    404: function () {
                        alert('web not found');
                    }
                },
            });
        }

        const mymap = L.map('mapid');
        mymap.on('load', function () {
            getIncidents(mymap.getBounds().toBBoxString());
        });
        mymap.setView([37.18,-3.6], 14);
		const incidentsLayerGroup = L.layerGroup().addTo(mymap);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
        }).addTo(mymap);

        // Tanto en cambio de zoom como al filtrar, se tienen en cuanta los filtros
        function callGetIncidents() {
            getIncidents(mymap.getBounds().toBBoxString(),
                $('#tipos-incid').val(),
                $('#date-from').val(),
                $('#date-to').val()
            );
            incidentsLayerGroup.clearLayers();
        }
        // Cada cambio de zoom limpia los marcadores y recarga los nuevos
        mymap.on('zoomend', function () {
            callGetIncidents();
        });

        $('#btn-filter-incident').click(function () {
            callGetIncidents();
        });

	</script>
@endsection