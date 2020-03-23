@extends('layouts.admin-base')

@section('title', 'Administración')
@section('username',$username)
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
{{--	<button type="button" data-toggle="modal" data-target="#caducidadModal">Configurar caducidad de incidentes</button>--}}

	<h2>Administración - Inicio</h2>
	<ul class="admin-list mt-4 pl-1">
		<li class="py-2">
			<a href="#" id="caduc" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar caducidad de incidentes</a>
		</li>
		<li class="py-2">
			<a href="#" id="contact-fav" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar número máximo de contactos favoritos</a>
		</li>
		<li class="py-2">
			<a href="#" id="zona-int" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar zonas de interés</a>
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
					<div class="row">
					</div>
				</div>
				<div class="modal-footer py-2">
					<button id="save-config" type="button" class="btn modal-button">Aceptar</button>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')

	<script>
		function printVarModal(modal, params){
		    let title = "";
		    switch (modal){
		        case 'caduc':
		            title = 'Caducidad de incidentes';
		            $('#caduc-radio').val(params['radio']);
                    $('#caduc-tiempo').val(params['tiempo']);
                    break;
                case 'contact-fav':
                    title = 'Número máximo de contactos favoritos';
                    $('#contact-fav-max').val(params['maximo']);
                    break;
                case 'zona-int':
                    title = 'Zonas de interés';
                    $('#zona-int-radio-min').val(params['radio_min']);
                    $('#zona-int-radio-max').val(params['radio_max']);
                    $('#zona-int-zonas-max').val(params['zonas_max']);
                    break;
                default:
                    alert('Error: no se ha podido recuperar el valor consultado.');
                    break;
		    }
            $('#config-title').html(title);
		}

		function getContentModal(modal){
		    let content = "";
            switch (modal){
                case 'caduc':
                    content = '<span class="col-4 text-left pl-4 my-auto">Radio</span>\n' +
                        '<input type="number" class="col-4 px-0" id="caduc-radio">\n' +
                        '<span class="col-4 text-left pl-2 my-auto">metros</span>\n' +
                        '<span class="col-4 text-left pl-4 my-auto">Tiempo</span>\n' +
                        '<input type="number" class="col-4 px-0" id="caduc-tiempo">\n' +
                        '<span class="col-4 text-left pl-2 my-auto">meses</span>';
                    break;
                case 'contact-fav':
                    content = '<span class="col-4 text-left pl-4 my-auto">Máximo</span>\n' +
                        '<input type="number" class="col-4 px-0" id="contact-fav-max">\n' +
                        '<span class="col-4 text-left pl-2 my-auto">contactos</span>';
                    break;
                case 'zona-int':
                    content = '<span class="col-5 text-left pl-4 my-auto">Radio mín.</span>\n' +
                        '<input type="number" class="col-4 px-0" id="zona-int-radio-min">\n' +
                        '<span class="col-3 text-left pl-2 my-auto">metros</span>\n' +
                        '<span class="col-5 text-left pl-4 my-auto">Radio máx.</span>\n' +
                        '<input type="number" class="col-4 px-0 my-2" id="zona-int-radio-max">\n' +
                        '<span class="col-3 text-left pl-2 my-auto">metros</span>\n' +
                        '<span class="col-5 text-left pl-4 my-auto">Zonas máx.</span>\n' +
                        '<input type="number" class="col-4 px-0 my-2" id="zona-int-zonas-max">\n' +
                        '<span class="col-3 text-left pl-2 my-auto">zonas</span>';
                    break;
                default:
                    alert('Error: no se ha podido establecer el modal.');
                    break;
            }
            return content;
		}

        $(".open-modal").click(function() {
	        let linkId = $(this).attr('id');
            let contentModal = getContentModal(linkId);
            $('.modal-body .row').html(contentModal);

            $.ajax({
                url: '/ajax_config',
                data: {
                    'modal': linkId
                },
                type: 'get',
                success: function (response) {
                    // alert(response);
                    // alert(response.msg);
                    // expandIncident(tabla, response.incidente);
                    printVarModal(linkId,response);
                },
                statusCode: {
                    404: function () {
                        alert('web not found');
                    }
                },
            });
        });
		
		$("#save-config").click(function () {
		    $.ajax({
                url: '/ajax_config',
                data: {
				    '_token': "{{csrf_token()}}",
                    'modal': linkId
                },
                type: 'post',
                success: function (response) {
                    // alert(response);
                    // alert(response.msg);
                    // expandIncident(tabla, response.incidente);
                    printVarModal(linkId,response);
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
