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

	<h2>Administración - Inicio</h2>
	<ul class="admin-list mt-4 pl-1">
		<li class="py-2">
			<a href="#" id="caduc" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar caducidad de incidentes</a>
		</li>
		<li class="py-2">
			<a href="#" id="cfav" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar número máximo de contactos favoritos</a>
		</li>
		<li class="py-2">
			<a href="#" id="zint" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configModal">Configurar zonas de interés</a>
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
					<div class="row" id="content-modal-id">
					</div>
				</div>
				<div class="modal-footer py-2">
					<button id="save-config" type="button" class="btn modal-button" data-dismiss="modal">Aceptar</button>
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
                case 'cfav':
                    title = 'Número máximo de contactos favoritos';
                    $('#cfav-maximo').val(params['maximo']);
                    break;
                case 'zint':
                    title = 'Zonas de interés';
                    $('#zint-radio_min').val(params['radio_min']);
                    $('#zint-radio_max').val(params['radio_max']);
                    $('#zint-zonas_max').val(params['zonas_max']);
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
                case 'cfav':
                    content = '<span class="col-4 text-left pl-4 my-auto">Máximo</span>\n' +
                        '<input type="number" class="col-4 px-0" id="cfav-maximo">\n' +
                        '<span class="col-4 text-left pl-2 my-auto">contactos</span>';
                    break;
                case 'zint':
                    content = '<span class="col-5 text-left pl-4 my-auto">Radio mín.</span>\n' +
                        '<input type="number" class="col-4 px-0" id="zint-radio_min">\n' +
                        '<span class="col-3 text-left pl-2 my-auto">metros</span>\n' +
                        '<span class="col-5 text-left pl-4 my-auto">Radio máx.</span>\n' +
                        '<input type="number" class="col-4 px-0 my-2" id="zint-radio_max">\n' +
                        '<span class="col-3 text-left pl-2 my-auto">metros</span>\n' +
                        '<span class="col-5 text-left pl-4 my-auto">Zonas máx.</span>\n' +
                        '<input type="number" class="col-4 px-0 my-2" id="zint-zonas_max">\n' +
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
            let configContainer = $('.modal-body .row');
            configContainer.html(contentModal);
	        configContainer.attr('id', linkId+"-container");

            $.ajax({
                url: '/ajax_config',
                data: {
                    'params': linkId
                },
                type: 'get',
                success: function (response) {
                    printVarModal(linkId,response);
                },
                statusCode: {
                    404: function () {
                        alert('web not found');
                    }
                },
            });
        });

        function getInputValues(){
            let inputValues = {}; // note this
            $('.modal-body .row :input').each(function(){
                // $( $(this).attr('id') ).text($(this).val());
                inputValues[$(this).attr('id').split('-')[1]] = ($(this).val()) ;
            });
            return inputValues;
        }

		$("#save-config").click(function () {
		    let configId = $('.modal-body .row').attr('id');
		    configId = configId.split("-container")[0];
            let inputValues = getInputValues();

		    $.ajax({
                url: '/ajax_config',
                data: {
				    '_token': "{{csrf_token()}}",
	                'configId': configId,
	                'values': inputValues
                },
                type: 'post',
                success: function (response) {
                    alert("Los parámetros se han actualizado correctamente");
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
