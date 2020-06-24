@extends('layouts.base')

@section('title', 'Mis publicaciones de incidentes')
@section('username',$username)

@section('content')
	<h2 class="section-title pl-4 px-md-1">Mis publicaciones de incidentes</h2>
	<section class="main-content mx-1 my-4">

		<div class="incidents">
			@if(!empty($incidents))
				@foreach($incidents as $inc)
					<article class="incident px-2 py-3 mb-1">
						<table class="w-100" cellpadding="8">
							<tbody>
							<tr>
								<td><h5>{{ucfirst($inc['incidente'])}}</h5></td>
								<td class="w-25">
									<small class="float-right">@dateTimeFormat($inc['fecha_hora'])</small>
									<small class="float-right">Subido: @dateFormat($inc['fecha_hora_subida'])</small>
								</td>
							</tr>
							<tr>
								<td class="pt-2">{{$inc['nombre_lugar']}}</td>
								<td class="w-25 pt-2 text-right">
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
			@else
				<article class="incident pt-3 text-center">
					<p>No has publicado ningún incidente</p>
				</article>
			@endif
		</div>
	</section>

@endsection

@section('scripts')
	<script>
	    var shareUrl = '{{ URL::asset('/images/icons/compartir.svg') }}';
	</script>

	<script src="{{asset('js/incidents/incidents.js')}}"></script>
@endsection
