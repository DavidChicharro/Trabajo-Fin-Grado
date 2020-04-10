<?php

namespace App\Http\Controllers;

use App\Delito;
use App\Incidente;
use App\Suben;
use Illuminate\Http\Request;
use App\User;

class IncidentsController extends Controller {
	private $numPags = 10;

	public function mapaIncidentes(Request $request) {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			/****/

			$req_date = $request['desde']!=null && $request['hasta']!=null;
			$req_type = $request['tipos_incidentes']!=null;

			if($req_date || $req_type){
				$range_id_delito = $req_type ? $request['tipos_incidentes'] : null;
				$range_date_delito = $req_date ? [$request['desde'], $request['hasta']] : null;

				if($req_date && $req_type){
					$incidents_pag = Incidente::whereIn('delito_id',$range_id_delito)
						->whereBetween('fecha_hora_incidente',$range_date_delito)
						->paginate($this->numPags);
				}elseif ($req_date && !$req_type){
					$incidents_pag = Incidente::whereBetween('fecha_hora_incidente',$range_date_delito)
						->paginate($this->numPags);
				}else{
					$incidents_pag = Incidente::whereIn('delito_id',$range_id_delito)
						->paginate($this->numPags);
				}
			}else{
				$incidents_pag = Incidente::all()->toArray();
			}

			$groupIncidents = Delito::all()->groupBy('id')->toArray();
			$incidentTypes = array();
			foreach ($groupIncidents as $id => $incident){
				$incidentTypes[$id] = $incident[0]['nombre_delito'];
			}
//dd($incidents_pag);
			if(!empty($incidents_pag)) {
				//if !oculto && !caducado
				foreach ($incidents_pag as $key => $inc) {
					$incidents[$key]['id'] = $inc['id'];
					$incidents[$key]['incidente'] = $incidentTypes[$inc['delito_id']];
					$incidents[$key]['lugar'] = $inc['latitud_incidente'] . ', ' . $inc['longitud_incidente'];
					$incidents[$key]['fecha_hora'] = $inc['fecha_hora_incidente'];
//					$incidents[$key]['lugar'] = ciudad-zona
//					$incidents[$key] = $inc;)
				}
//				dd($result);
//				dd($incidents);
			}else{
				$incidents = [];
			}

			/****/

			// Quizás no sea necesario devolver la sesión (email)
			$result = compact(['username', 'notifications', 'incidents', 'incidentTypes']);
			return view('incidents.map', $result);
		}
		return redirect()->route('index');
	}

	public function getMapIncidents(Request $request) {
//		$req_date = $request['desde']!=null && $request['hasta']!=null;
//		$req_type = $request['tipos_incidentes']!=null;
		if(!is_null($request['mapLimits'])){
			$allIncidents = Incidente::all()->toArray();
//			dd($request['mapLimits']);
			//Añado un margen de 1.0 para cargar incidentes de alrededor de la vista
			$westLimit = floatval($request['mapLimits'][0])-1.0;
			$southLimit = floatval($request['mapLimits'][1])-1.0;
			$eastLimit = floatval($request['mapLimits'][2])+1.0;
			$northLimit = floatval($request['mapLimits'][3])+1.0;

			$groupIncidents = Delito::all()->groupBy('id')->toArray();
			$incidentTypes = array();
			foreach ($groupIncidents as $id => $incident){
				$incidentTypes[$id] = $incident[0]['nombre_delito'];
			}

			$incidents = [];
			foreach($allIncidents as $key => $incident){
				$latInc = floatval($incident['latitud_incidente']);
				$longInc = floatval($incident['longitud_incidente']);
//				dd($latInc, $longInc, $northLimit);
				if($latInc < $northLimit && $latInc > $southLimit &&
				$longInc < $eastLimit && $longInc > $westLimit){
//					dd($incident);
					$incidents[$key]['id'] = $incident['id'];
					$incidents[$key]['incidente'] = ucfirst($incidentTypes[$incident['delito_id']]);
					$incidents[$key]['latitud'] = $latInc;
					$incidents[$key]['longitud'] = $longInc;
					$incidents[$key]['fecha_hora'] = $incident['fecha_hora_incidente'];
					$incidents[$key]['descripcion'] = $incident['descripcion_incidente'];
				}
			}
			return json_encode($incidents);
		}
		return null;
	}

	public function listaIncidentes(Request $request) {
		$session = session('email');

		if(isset($session)) {
			/** -- AÑADIR DATOS PARA DEVOLVER A LA VISTA QUE SE HA REALIZADO UN FILTRO -- */
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$req_date = $request['desde']!=null && $request['hasta']!=null;
			$req_type = $request['tipos_incidentes']!=null;

			if($req_date || $req_type){
				$range_id_delito = $req_type ? $request['tipos_incidentes'] : null;
				$range_date_delito = $req_date ? [$request['desde'], $request['hasta']] : null;

				if($req_date && $req_type){
					$incidents_pag = Incidente::whereIn('delito_id',$range_id_delito)
					->whereBetween('fecha_hora_incidente',$range_date_delito)
					->paginate($this->numPags);
				}elseif ($req_date && !$req_type){
					$incidents_pag = Incidente::whereBetween('fecha_hora_incidente',$range_date_delito)
						->paginate($this->numPags);
				}else{
					$incidents_pag = Incidente::whereIn('delito_id',$range_id_delito)
						->paginate($this->numPags);
				}
			}else{
				$incidents_pag = Incidente::paginate($this->numPags);
			}

			$groupIncidents = Delito::all()->groupBy('id')->toArray();
			$incidentTypes = array();
			foreach ($groupIncidents as $id => $incident){
				$incidentTypes[$id] = $incident[0]['nombre_delito'];
			}

			if($incidents_pag->total() != 0) {
				//if !oculto && !caducado
				foreach ($incidents_pag->items() as $key => $inc) {
					$incidents[$key]['id'] = $inc['id'];
					$incidents[$key]['incidente'] = $incidentTypes[$inc['delito_id']];
					$incidents[$key]['lugar'] = $inc['latitud_incidente'] . ', ' . $inc['longitud_incidente'];
					$incidents[$key]['fecha_hora'] = $inc['fecha_hora_incidente'];
//					$incidents[$key]['lugar'] = ciudad-zona
//					$incidents[$key] = $inc;)
				}
//				dd($result);
//				dd($incidents);
			}else{
				$incidents = [];
			}

			$result = compact(['username','incidents','incidents_pag','incidentTypes', 'notifications']);
			return view('incidents.list', $result);
		}
		return redirect()->route('index');
	}

	public function getIncidentDetails(Request $request) {
//    	$d = Delito::orderBy('id','desc')->first();
//		DB::select("")
//		dd($request);
		$input = $request->all();
		$incident_id = $input['incidentId'];

		$inc = Incidente::where('id',$incident_id)->first();

		$incidente['descripcion'] = $inc['descripcion_incidente'];

		$var = "Estoy en el index del controlador de Incidentes - AJAX";
		return response()->json(array('msg'=>$var, 'incidente'=>$incidente), 200);
//    	return "Estoy en el index del controlador de AJAX";
//    	dd($var);
//    	echo $var;
	}

	public function create(Request $request) {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$delitos_cat = Delito::groupBy('categoria_delito')
				->value('categoria_delito');

				//Provisional -> hasta que haya más
					$delitos = ['contra el honor','contra la verdad'];
					array_push($delitos, $delitos_cat);
//			dd($delitos_cat);

			return view('incidents.new-incident',compact(['delitos','username']));
		}

//		$datos = $request->validate([
//			'email' => 'bail|required|min:7|max:255',
//			'password' => 'bail|required|min:8',
//		]);
//
//		$user_exist = User::where('email',$datos['email'])->count();
//
//		if($user_exist==0){
//			return view('register-step-2')
//				->with('datos',$datos);
//		}else{
//			return redirect()->back()
//				->withErrors([
//					'message'=>'¡El usuario introducido ya se encuentra registrado!'
//				]);
//		}
	}

	public function store(Request $request) {
		$session = session('email');

		if(isset($session)){
			$datos = $request->validate([
				'delito' => 'bail|required',
				'fecha_incidente' => 'bail|required|date|before:tomorrow',
				'hora_incidente' => 'bail|required',
				'lugar' => 'bail|required',
				'descripcion_incidente' => 'bail|required|min:10|max:1000',
				'afectado_testigo' => 'bail|required|boolean'
			]);

			$lastId = Incidente::orderBy('id', 'desc')->value('id');
			$lat_long_site = explode(",", $datos['lugar']);
			$fecha_hora = $datos['fecha_incidente']." ".$datos['hora_incidente'];
			$agravantes= isset($request['agravantes']) ? implode(",", $request['agravantes']) : null;

			$incInput = array(
				'id' => $lastId+1,
				'delito_id' => $datos['delito'],
				'latitud_incidente' => $lat_long_site[0],
				'longitud_incidente' => $lat_long_site[1],
				'fecha_hora_incidente' => $fecha_hora,
				'descripcion_incidente' => $datos['descripcion_incidente'],
				'afectado_testigo' => $datos['afectado_testigo'],
				'agravantes' => $agravantes
			);

			$userId = User::where('email', $session)->value('id');
			$upInput = array(
				'usuario_id' => $userId,
				'delito_id' => $datos['delito'],
				'incidente_id' => $lastId+1
			);

			Incidente::create($incInput);
			Suben::create($upInput);

			return redirect()
				->route('mapaIncidentes')
				->with('message', 'Incidente registrado');
		}
		return redirect()->route('index');
	}

	public function getDelitos(Request $request) {
		if(isset($request['delitos'])){
//			dd($request['delitos']);
			$delitos = Delito::whereIn('categoria_delito',$request['delitos'])->get();
//				->value('categoria_delito');
//			dd($delitos);
			return $delitos;
		}
	}

	public function incidentesSubidos(Request $request) {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$uploaded = Suben::all()->where('usuario_id', $user['id']);
//			dd($uploaded);
			$range_incidents = array();
			$date_upload = array();
			foreach ($uploaded as $up) {
				array_push($range_incidents, $up['incidente_id']);
				$date_upload[$up['incidente_id']] = $up['fecha_hora_sube_incidente'];
			}

			$incidents_pag = Incidente::whereIn('id',$range_incidents)
				->paginate($this->numPags);

			$groupIncidents = Delito::all()->groupBy('id')->toArray();
			$incidentTypes = array();
			foreach ($groupIncidents as $id => $incident){
				$incidentTypes[$id] = $incident[0]['nombre_delito'];
			}

			if($incidents_pag->total() != 0) {
				//if !oculto && !caducado
				foreach ($incidents_pag->items() as $key => $inc) {
					$incidents[$key]['id'] = $inc['id'];
					$incidents[$key]['incidente'] = $incidentTypes[$inc['delito_id']];
					$incidents[$key]['lugar'] = $inc['latitud_incidente'] . ', ' . $inc['longitud_incidente'];
					$incidents[$key]['fecha_hora'] = $inc['fecha_hora_incidente'];
					$incidents[$key]['fecha_hora_subida'] = $date_upload[$inc['id']];
//					$incidents[$key]['lugar'] = ciudad-zona
//					$incidents[$key] = $inc;)
				}
//				dd($result);
//				dd($incidents);
			}else{
				$incidents = [];
			}
//			dd($incidents);

			$result = compact(['username', 'incidents', 'incidents_pag', 'notifications']);

			return view('incidents.uploaded', $result);
		}
		return redirect()->route('index');
	}
}
