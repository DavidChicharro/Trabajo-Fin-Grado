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
	{{--	<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />--}}
@endsection

@section('content')
	<h2>Añadir zona de interés</h2>
	<section class="main-content mx-1">
		<div id="mapid" class="map-cursor-pointer my-4" style="height: 500px"></div>
		<form class="px-3 pr-5" method="post" action="{{ Request::url() }}">
			@csrf

			<div class="form-group">
				<input type="text" name="lat_zona_int" id="lat_zona_int" class="form-control" {{--hidden--}}>
				<input type="text" name="long_zona_int" id="long_zona_int" class="form-control" {{--hidden--}}>
				<input type="text" name="nombre_lugar" id="nombre_lugar" class="form-control" {{--hidden--}}>
				<input type="number" name="radio_zona_int" id="radio_zona_int" class="form-control"
				       min="{{$config['radio_min']}}" max="{{$config['radio_max']}}" {{--hidden--}}>

			</div>

			<input type="submit" value="Añadir zona de interés" class="form-button col-md-6">
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

	<!-- Leaflet JS Control Geocoder -->
	{{--<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>--}}

	<script>
        const mymap = L.map('mapid').setView([37.18,-3.6], 14);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
        }).addTo(mymap);

        var searchControl = new L.esri.Controls.Geosearch().addTo(mymap);
        var incidentsLayerGroup = new L.LayerGroup().addTo(mymap);

        mymap.on('click', function (e) {
            let latLng = mymap.mouseEventToLatLng(e.originalEvent);
            incidentsLayerGroup.clearLayers();
            // if(!settedMarker)
            //     settedMarker = true;

            // $('#lugar').val(
            //     parseFloat(latLng.lat).toFixed(4) +','+
            //     parseFloat(latLng.lng).toFixed(4)
            // );
            $('#lat_zona_int').val(
                parseFloat(latLng.lat).toFixed(4)
            );
            $('#long_zona_int').val(
                parseFloat(latLng.lng).toFixed(4)
            );
            let placeName = "";
            $.ajax({
                url: 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
                    latLng.lat + '&lon=' + latLng.lng,
                success: function(data){
                    placeName = ((typeof(data.address.locality)!== 'undefined')?data.address.locality +", ":"")+
                        ((typeof(data.address.city_district)!== 'undefined')?data.address.city_district +", ":"")+
                        ((typeof(data.address.village)!== 'undefined')?data.address.village:"")+
                        ((typeof(data.address.town)!== 'undefined')?data.address.town:"")+
                        ((typeof(data.address.city)!== 'undefined')?data.address.city:"")+
                        ((typeof(data.address.county)!== 'undefined')?" ("+data.address.county +")":"");

                    $('#nombre_lugar').val((placeName !== "")?placeName:data.address.country);
                },
                error : function() {
                    placeName = latLng.lat + ', ' + latLng.lng;
                    $('#nombre_lugar').val(placeName);
                }
            });

            L.marker(latLng).addTo(incidentsLayerGroup);
        });

	</script>

	<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
	<!-- Latest compiled and minified JavaScript translation files -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-es_ES.min.js"></script>

@endsection