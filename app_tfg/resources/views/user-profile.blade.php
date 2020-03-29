@extends('layouts.base')

@section('title', 'Zona personal')
@section('username',$username)

@section('stylesheet')
	<link href="{{asset('css/forms.css')}}" rel="stylesheet"/>
@endsection


@section('content')
	<h2>Zona personal</h2>
	<section class="main-content pl-3 pt-4">
		<article class="my-2">
			<h5 class="p-1">Configuración</h5>
			<ul class="admin-list pl-3">
				<li class="py-1">
					<a href="#" id="panicact" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configUser">Establecer acción de pánico</a>
				</li>
				<li class="py-1">
					<a href="#" id="secretpin" class="open-modal li-as-lk py-2" data-toggle="modal" data-target="#configUser">Establecer PIN secreto</a>
				</li>
			</ul>

			<!-- Modal para ver y establecer parámetros de configuración -->
			<div class="modal fade" id="configUser" tabindex="-1" role="dialog" aria-hidden="true">
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
		</article>

		<article class="my-4">
			<h5 class="p-1">Nombre</h5>
			<form class="pl-3 pr-5 w-50" method="post" action="{{ Request::url() }}">
				@csrf
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" value="{{ $user['nombre'] }}" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="apellidos">Apellidos</label>
					<input type="text" name="apellidos" value="{{ $user['apellidos'] }}" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" name="email" value="{{ $user['email'] }}" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="dni">D.N.I. / N.I.E.</label>
					<input type="text" name="dni_" value="{{ $user['dni'] }}" class="form-control" readonly>
				</div>

				<div class="form-group">
					<label for="fecha_nacimiento">Fecha de nacimiento</label>
					<input type="date" name="fecha_nacimiento" value="@dateInputFormat($user['fecha_nacimiento'])" class="form-control">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="telefono">Número de teléfono móvil</label>
					<input type="tel" name="telefono" value="{{ $user['telefono'] }}" class="form-control" maxlength="9">
					<div class="my-auto text-center req-icon">
						<img class="img-fluid w-50" src="{{asset('images/icons/required.svg')}}">
					</div>
				</div>

				<div class="form-group">
					<label for="telefono_fijo">Número de teléfono fijo</label>
					<input type="tel" name="telefono_fijo" value="{{ $user['telefono_fijo'] }}" class="form-control" maxlength="9">
				</div>

				<input type="submit" value="Guardar cambios" class="form-button" name="formData">
			</form>
		</article>

		<div>
			@if($errors->any())
				@foreach($errors->all() as $error)
					<script>alert('{{$error}}')</script>
				@endforeach
			@endif
		</div>

		<article class="my-4">
			<h5 class="p-1">Seguridad</h5>
			<form class="pl-3 pr-5 w-50" method="post" action="{{ Request::url() }}">
				@csrf
				<div class="form-group">
					<label for="password">Contraseña actual</label>
					<input type="password" name="password" class="form-control">
				</div>

				<div class="form-group">
					<label for="new_password">Nueva contraseña</label>
					<input type="password" name="new_password" class="form-control">
				</div>

				<div class="form-group">
					<label for="conf_password">Confirmar contraseña</label>
					<input type="password" name="conf_password" class="form-control">
				</div>

				<input type="submit" value="Cambiar contraseña" class="form-button" name="formPass" disabled>
			</form>
		</article>

		<article class="my-4">
			<h5 class="p-1">Cuenta</h5>
			<a href="#" class="text-danger px-3">Cerrar cuenta</a>
		</article>
	</section>

@endsection


@section('scripts')
	<script src="{{asset('js/user-profile.js')}}"></script>
	<script src="{{asset('js/bootstrap.js')}}"></script>
	<script>
        function printVarModal(modal, params){
            let title = "";
            switch (modal){
                case 'secretpin':
                    title = 'Establecer PIN secreto';
                    let pin = (params==null) ? "":params;
                    $('#pin-number').val(pin);
                    break;
                case 'panicact':
                    title = 'Establecer acción de pánico';
                    let action = (params==null) ? "":params;
                    console.log(action);
                    switch(action){
                        case 'ubicacion':
                            $('#panicact-ubi').prop("checked",true);
                            break;
                        case 'llamada':
                            $('#panicact-call').prop("checked",true);
                            break;
	                    default:
                            $('#panicact-not').prop("checked",true);
                            break;
                    }
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
                case 'secretpin':
                    content = '<span class="offset-2 col-2 text-left px-1 my-auto">PIN</span>\n' +
                        '<input type="text" class="col-4 px-0" id="pin-number" maxlength="4" pattern="[0-9]{4}" title="4 números">';
                    break;
                case 'panicact':
                    content = '<div class="form-check offset-2 col-9 p-1">\n' +
                        '    <input type="radio" id="panicact-not" name="panicact" value="" class="form-check-input">\n' +
                        '    <label for="not-act" class="form-check-label ml-2">Ninguna</label>\n' +
                        '  </div>\n' +
                        '  <div class="form-check offset-2 col-9 p-1">\n' +
                        '    <input type="radio" id="panicact-ubi" name="panicact" value="ubicacion" class="form-check-input">\n' +
                        '    <label for="not-act" class="form-check-label ml-2">Enviar ubicación</label>\n' +
                        '  </div>\n' +
                        '  <div class="form-check offset-2 col-9 p-1">\n' +
                        '    <input type="radio" id="panicact-call" name="panicact" value="llamada" class="form-check-input">\n' +
                        '    <label for="not-act" class="form-check-label ml-2">Llamar a contactos</label>\n' +
                        '  </div>';
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
                url: '/user_config',
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
            // COMPROBAR QUE EL FORMATO DEL PIN ES CORRECTO

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