<?php

namespace App\Http\Controllers;

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
			$username = User::where('email', $session)->first()->value('nombre');
			
			$incidentes = Incidente::all();
//			dd($incidents);
			//if !oculto && !caducado
			foreach($incidentes as $key => $inc){
//				$incidents[$key]['incidente'] = delito_incidente
//				$incidents[$key]['lugar'] = ciudad-zona
//				$incidents[$key]['fecha_hora'] = formatear_en_blade
//				$incidents[$key]['lugar'] = ciudad-zona
				$incidents[$key] = $inc;
			}
//			dd($result);

			// Quizás no sea necesario devolver la sesión (email)
			$result = compact(['session', 'username','incidents']);
			return view('incidents.list', $result);
		}
		return redirect()->route('index');
	}
}
