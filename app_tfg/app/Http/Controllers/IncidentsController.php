<?php

namespace App\Http\Controllers;

use App\Delito;
use App\Incidente;
use Illuminate\Http\Request;
use App\User;

class IncidentsController extends Controller {
	public function mapaIncidentes() {
		$session = session('email');

		if(isset($session)) {
			$username = User::where('email', $session)->first()->value('nombre');

			// Quizás no sea necesario devolver la sesión (email)
			$result = compact(['session', 'username']);
			return view('incidents.map', $result);
		}
		return redirect()->route('index');
	}

	public function listaIncidentes() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			
//			$incidentes = Incidente::all();
			$incidents_pag = Incidente::paginate(10);

//			dd($incidentes->items());
			//if !oculto && !caducado
			foreach($incidents_pag->items() as $key => $inc){
				$incidents[$key]['id'] = $inc['id'];
//				$incidents[$key]['incidente'] = delito_incidente
				$incidents[$key]['lugar'] = $inc['latitud_incidente'].', '.$inc['longitud_incidente'];
				$incidents[$key]['fecha_hora'] = $inc['fecha_hora_incidente'];
//				$incidents[$key]['lugar'] = ciudad-zona
//				$incidents[$key] = $inc;
			}
//			dd($result);
//			dd($incidents);

			// Quizás no sea necesario devolver la sesión (email)
			$result = compact(['session', 'username','incidents','incidents_pag']);
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

	public function store() {

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
}
