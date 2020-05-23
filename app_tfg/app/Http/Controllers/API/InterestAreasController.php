<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\InterestAreasController as IntAreasCtrl;
use App\User;
use App\ZonasInteres;
use Illuminate\Http\Request;

class InterestAreasController extends Controller
{
	public function getInterestAreas(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user)) {
				$intAreaCtrl = new IntAreasCtrl();
				$resInterestAreas = $intAreaCtrl->getUserInterestAreas($user['id']);

				$interestAreas = $resInterestAreas['interestAreas'];
				$bounds = $resInterestAreas['bounds'];

				return response()
					->json([
						'status' => 'success',
						'interestAreas' => $interestAreas->toJson(),
						'bounds' => $bounds
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function newArea(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user)) {
				$ctrl = new AjaxController();
				$config = $ctrl->getZonasInteresParms();

				$numIntAreasUser = ZonasInteres::where('usuario_id', $user['id'])->count();

				if($numIntAreasUser >= $config['zonas_max'])
					return response()
						->json([
							'status' => 'error',
							'message' => '¡Tienes el número máximo de zonas de interés permitidas!'
						], 200);

				return response()
					->json([
						'status' => 'success',
						'config' => $config,
						'numInterestAreas' => $numIntAreasUser
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}

	public function store(Request $request) {
		if (isset($request['email'])) {
			$user = User::where('email', $request['email'])->first();

			if (!is_null($user)) {
				$datos = $request->validate([
					'lat_zona_int' => 'bail|required',
					'long_zona_int' => 'bail|required',
					'radio_zona_int' => 'bail|required|integer|min:10|max:100000'
				]);

				$input = array(
					'usuario_id' => $user['id'],
					'latitud_zona_interes' => $datos['lat_zona_int'],
					'longitud_zona_interes' => $datos['long_zona_int'],
					'nombre_zona_interes' => $request['nombre_lugar'],
					'radio_zona_interes' => $datos['radio_zona_int'],
				);

				ZonasInteres::create($input);

				return response()
					->json([
						'status' => 'success'
					], 200);
			}
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 200);
	}
}
