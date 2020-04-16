<?php

namespace App\Http\Controllers;

use App\User;
use App\ZonasInteres;
use Illuminate\Http\Request;

class InterestAreasController extends Controller
{
	/**
	 * Devuelve la vista de las zonas de interés
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
    public function zonasInteres() {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$numInterestAreas = ZonasInteres::where('usuario_id', $user['id'])->count();

			$result = compact(['username', 'notifications', 'numInterestAreas']);
			return view('interest_areas.areas', $result);
		}
		return redirect()->route('index');
	}

	/**
	 * Devuelve las zonas de interés de un usuario
	 *
	 * @param Request $request
	 * @return array|string|null
	 */
    public function getInterestAreas(Request $request) {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();

			$interestAreas = ZonasInteres::where('usuario_id', $user['id'])->get();

			$bounds = array(
				'north' => "-90",
				'east' => "-180",
				'south' => "90",
				'west' => "180"
			);
			foreach ($interestAreas as $area){
				if($area['latitud_zona_interes']>$bounds['north'])
					$bounds['north'] = $area['latitud_zona_interes'];
				if($area['latitud_zona_interes']<$bounds['south'])
					$bounds['south'] = $area['latitud_zona_interes'];
				if($area['longitud_zona_interes']>$bounds['east'])
					$bounds['east'] = $area['longitud_zona_interes'];
				if($area['longitud_zona_interes']<$bounds['west'])
					$bounds['west'] = $area['longitud_zona_interes'];
			}

			return array(
				'interestAreas' => $interestAreas->toJson(),
				'bounds' => json_encode($bounds),
				'empty' => empty($interestAreas->toArray())
			);
		}
		return null;
	}

	public function removeInterestArea(Request $request){
		$session = session('email');

		if(isset($session) && !is_null($request['idIntArea'])) {
			$user = User::where('email', $session)->first();

			$interestAreas = ZonasInteres::where('usuario_id', $user['id'])
				->where('id', $request['idIntArea'])->delete();

			return "success";
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

			$numInteresAreasUser = ZonasInteres::where('usuario_id', $user['id'])->count();
			if($numInteresAreasUser >= $config['zonas_max'])
				return redirect()->back()->with([
					'error'=>'¡Tienes el número máximo de zonas de interés permitidas!'
				]);

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
