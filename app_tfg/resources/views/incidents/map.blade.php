@extends('layouts.base')

@section('title', 'Mapa de incidentes')
@section('username',$username)
@section('stylesheet')
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	      crossorigin=""/>
@endsection

@section('content')
	<h2>Mapa de incidentes</h2>
	<section class="main-content mx-1">
{{--		<img class="img-fluid w-100" src="{{asset('images/mapa-grx.png')}}">--}}
		<div id="mapid" style="height: 500px"></div>

		<div class="my-3">
			<a href="/mis-publicaciones-incidentes">Mis publicaciones de incidentes</a>
			<a class="float-right" href="/lista-incidentes">Ver lista</a>
		</div>

		<div>
		</div>
	</section>

@endsection

@section('scripts')
	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
	        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
	        crossorigin=""></script>

	<script>
		function getIncidents(bounds) {
		    let mapLimits = bounds.split(',');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/get_map_incidents',
                data: {
                    'mapLimits': mapLimits
                },
                type: 'post',
                success: function (response) {
                    console.log(response);
                    //if response!=null
                    let result = JSON.parse(response);
                    $.each(result, function(index, value){
                        console.log(index, value);
                        L.marker([value.latitud, value.longitud])
	                        .bindPopup('<b>'+value.incidente+'</b>  -  '+
		                        value.fecha_hora+'.<br>'+value.descripcion)
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


        // var marker = L.marker([51.5, -0.09]).addTo(mymap);
        // var popup = L.popup()
        //     .setLatLng([51.5, -0.09])
        //     .setContent("I am a standalone popup.")
        //     .openOn(mymap);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
        }).addTo(mymap);

        mymap.on('zoomend', function () {
			console.log(mymap.getBounds());
            getIncidents(mymap.getBounds().toBBoxString());
            incidentsLayerGroup.clearLayers();
        });



	</script>
@endsection