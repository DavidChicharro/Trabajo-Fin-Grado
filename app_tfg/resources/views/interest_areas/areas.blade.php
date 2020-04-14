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

	<script>
        function getInterestAreas() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/get_interest_areas',
                type: 'post',
                success: function (response) {
                    if(!response.empty) {
                        let result = JSON.parse(response.interestAreas);
                        $.each(result, function (index, value) {
                            let marker = L.marker([value.latitud_zona_interes, value.longitud_zona_interes])
                                .bindPopup('<b>' + value.nombre_zona_interes + '</b><br>'
                                    + 'Radio: ' + value.radio_zona_interes + ' m');
                            let radius = L.circle([value.latitud_zona_interes, value.longitud_zona_interes],
                                value.radio_zona_interes, {color: "red"});
                            intAreasLayerGroup.addLayer(L.layerGroup([marker, radius]));
                        });

                        let bounds = JSON.parse(response.bounds);
                        mymap.fitBounds([
                            [bounds.south, bounds.west],
                            [bounds.north, bounds.east]
                        ], {maxZoom: 15});

                        fillOptions(response.interestAreas);
                    }else{
                        location.reload();
                    }
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
            getInterestAreas();
        });
        mymap.setView([37.18,-3.6], 14);
        const intAreasLayerGroup = L.layerGroup().addTo(mymap);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
        }).addTo(mymap);


        function fillOptions(zonas){
            let output = "";
            JSON.parse(zonas).forEach(function (e) {
                let idOpt = "";
                let valOpt = "";
                $.each(e, function (key, value) {
                    if(key==='id')
                        idOpt = value;
                    else if(key==='nombre_zona_interes')
                        valOpt = value;
                });
                output+='<option value="'+idOpt+'">'+valOpt+'</option>';
            });
            $('#zonas_interes').html(output);
            $('#zonas_interes').selectpicker('refresh');
        }

        // Devuelve el valor del elemento seleccionado en el menú desplegable
        $(function () {
            $("#zonas_interes").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
                // console.log(this.value, clickedIndex, newValue, oldValue);
                let bin = $('[id^=remove-interest-area]');
                bin.removeClass('d-none');
                bin.attr('id', 'remove-interest-area-'+this.value);
            });
        });

        // Elimina la zona de interés al clicar en la papelera
        $('[id^=remove-interest-area]').click(function () {
            let idIntArea = $(this).attr('id').split('-')[3];
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/remove_interest_area',
                data: {
                    'idIntArea' : idIntArea
                },
                type: 'post',
                success: function (response) {
                    if(response==="success"){
                        getInterestAreas();
                        intAreasLayerGroup.clearLayers();
                        let bin = $('[id^=remove-interest-area]');
                        bin.addClass('d-none');
                        bin.attr('id', 'remove-interest-area');
                    }
                },
                statusCode: {
                    404: function () {
                        alert('web not found');
                    }
                },
            });
        });

	</script>
@endsection