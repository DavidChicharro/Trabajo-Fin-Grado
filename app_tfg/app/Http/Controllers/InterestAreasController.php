<?php

namespace App\Http\Controllers;

use App\User;
use App\ZonasInteres;
use Illuminate\Http\Request;

class InterestAreasController extends Controller
{
    public function zonasInteres() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$numInterestAreas = ZonasInteres::where('usuario_id', $user['id'])->count();

//			dd($numInterestAreas);
//			$interestAreas = array();
//			foreach ($groupIncidents as $id => $incident){
//				$incidentTypes[$id] = $incident[0]['nombre_delito'];
//			}

			$result = compact(['username', 'notifications', 'numInterestAreas']);
			return view('interest_areas.areas', $result);
		}
		return redirect()->route('index');
	}

    public function getInterestAreas(Request $request) {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();

			return ZonasInteres::where('usuario_id', $user['id'])->get()->toJson();
		}
		return null;
	}

	public function nuevaZonaInteres() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$controller = new AjaxController();
			$config = $controller->getZonasInteresParms();

			/** ---------------------------- **/
			//Contar zonas de interés del usuario y prohibir añadir más si las supera
			/** ---------------------------- **/

			$result = compact(['username', 'notifications', 'config']);
			return view('interest_areas.new-area', $result);
		}
		return redirect()->route('index');
	}

	public function store(Request $request) {
		$session = session('email');

		if(isset($session)){
			$datos = $request->validate([
				'lat_zona_int' => 'bail|required',
				'long_zona_int' => 'bail|required',
				'radio_zona_int' => 'bail|required|integer|min:10|max:100000'
			]);

			$userId = User::where('email', $session)->value('id');
			$input = array(
				'usuario_id' => $userId,
				'latitud_zona_interes' => $datos['lat_zona_int'],
				'longitud_zona_interes' => $datos['long_zona_int'],
				'nombre_zona_interes' => $request['nombre_lugar'],
				'radio_zona_interes' => $datos['radio_zona_int'],
			);

			ZonasInteres::create($input);

			return redirect()
				->route('zonasInteres')
				->with('message', 'Zona de interés añadida');
		}
		return redirect()->route('index');
	}
}
