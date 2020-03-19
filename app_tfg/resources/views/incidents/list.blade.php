@extends('layouts.base')

@section('title', 'Mapa de incidentes')
@section('username',$username)


@section('content')
	<h2>Lista de incidentes</h2>
	<section class="main-content mx-1">
		<div class="my-3 w-75">
			<a href="#">Mis publicaciones de incidentes</a>
			<a class="float-right" href="/mapa-incidentes">Ver mapa</a>
		</div>
		<div class="ajax">

		</div>
		<div class="incidents">
			@foreach($incidents as $inc)
				<article class="incident px-2 py-3 mb-1 w-75">
					<table class="w-100">
						<tbody>
							<tr>
								<td><h5>Incidente {{$inc['id']}}</h5></td>
								<td class="w-25"><small class="float-right">@dateTimeFormat($inc['fecha_hora'])</small></td>
							</tr>
							<tr>
								<td class="pt-3">{{$inc['lugar']}}</td>
								<td class="w-25 pt-3 text-right">
									<span id="vm{{$inc['id']}}" class="view-more text-right sp-as-lk">Ver más</span>
								</td>
							</tr>
						</tbody>
					</table>
				</article>
			@endforeach
			<div class="m-3 w-75">
				{{ $incidents_pag->links() }}
			</div>
		</div>
	</section>

@endsection

@section('scripts')
	<script>
		// $(document).ready(function () {
		// 	alert("Inside");
		// });

		// $("#vm").click(function() {
		//     alert("Click!");
		// });

		// $.ajaxSetup({
		// 	headers: {
		// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// 	}
		// });

		function expandIncident(tabla, incidente){
		    // console.log(tabla);
		    // console.log(incidente);
			let descRow = '<tr class="expanded"><td colspan="2" class="pt-3">'+incidente.descripcion+'</td></tr>';
			//si en shareLessRow no utilizo comilla simple, sustitutir los escapes (\)
			let shareLessRow = '<tr class="expanded"><td class="pt-4">Compartir incidente</td><td class="text-right pt-4"><span id="vl" class="view-less text-right sp-as-lk">Ver menos</span></td></tr>';
			tabla.after(shareLessRow).delay(500);
            tabla.after(descRow).delay(500);
        }

        function contractIncident(){
            $(".expanded").hide(300, "linear"); //Oculta todas las rows
            $(".view-more-loaded").show(300);   //Muestro botón "ver más" del resto
        }

        $(document).on("click",".view-less", function () {
	        contractIncident();
        });

        $(".view-more").click(function() {
            contractIncident();
            $(this).hide(); //escondo boton "Ver más"

	        if(!$(this).hasClass("view-more-loaded")) {
                $(this).addClass("view-more-loaded");
                let buttonId = $(this).attr('id');
                let incidentId = buttonId.replace('vm', '');
                let tabla = $(this).parent().parent().parent();

                console.log($(this));
                $.ajax({
                    url: '/get_incident_details',
                    data: {
                        '_token': "{{csrf_token()}}",
                        'incidentId': incidentId
                    },
                    type: 'post',
                    success: function (response) {
                        // alert(response);
                        // alert(response.msg);
                        expandIncident(tabla, response.incidente);
                    },
                    statusCode: {
                        404: function () {
                            alert('web not found');
                        }
                    },
                });
            }
            let antecessor = $(this).parent().parent().parent();
	        $(antecessor).siblings(".expanded").show(300);
		});
	</script>
@endsection
