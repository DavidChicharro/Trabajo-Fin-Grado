<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IncidentsController as IncidentCtrl;
use App\Http\Controllers\UserNotificationsController;
use App\Incidente;
use App\Suben;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentsController extends Controller
{
	private function getListIncidents($incidentsAll, $incidentTypes, $date_upload=null) {
		if(!empty($incidentsAll->count() > 0)) {
			foreach ($incidentsAll as $key => $inc) {
				$incidents[$key]['id'] = $inc['id'];
				$incidents[$key]['delito_id'] = $inc['delito_id'];
				$incidents[$key]['incidente'] = $incidentTypes[$inc['delito_id']];
				$incidents[$key]['latitud'] = floatval($inc['latitud_incidente']);
				$incidents[$key]['longitud'] = floatval($inc['longitud_incidente']);
				$incidents[$key]['fecha_hora'] = $inc['fecha_hora_incidente'];
				$incidents[$key]['nombre_lugar'] = $inc['nombre_lugar'];
				$incidents[$key]['descripcion'] = $inc['descripcion_incidente'];
				if(!is_null($date_upload))
					$incidents[$key]['fecha_hora_subida'] = $date_upload[$inc['id']];
			}
			return $incidents;
		}else{
			return [];
		}
	}
	
    public function getList(Request $request) {
		$req_date = $request['desde']!=null && $request['hasta']!=null;
		$req_type = $request['tipos_incidentes']!=null;

		if($req_date || $req_type){
			$range_id_delito = $req_type ? $request['tipos_incidentes'] : null;
			$range_date_delito = $req_date ? [$request['desde'], $request['hasta']] : null;

			if($req_date && $req_type){
				$incidentsAll = Incidente::whereIn('delito_id',$range_id_delito)
					->whereBetween('fecha_hora_incidente',$range_date_delito)
					->where('oculto', 0)->where('caducado', 0)
					->orderBy('fecha_hora_incidente', 'desc')
					->get();
			}elseif ($req_date && !$req_type){
				$incidentsAll = Incidente::whereBetween('fecha_hora_incidente',$range_date_delito)
					->where('oculto', 0)->where('caducado', 0)
					->orderBy('fecha_hora_incidente', 'desc')
					->get();
			}else{
				$incidentsAll = Incidente::whereIn('delito_id',$range_id_delito)
					->where('oculto', 0)->where('caducado', 0)
					->orderBy('fecha_hora_incidente', 'desc')
					->get();
			}
		}else{
			$incidentsAll = Incidente::where('oculto', 0)->where('caducado', 0)
				->orderBy('fecha_hora_incidente', 'desc')
				->get();
		}

		$incCtrl = new IncidentCtrl();
		$incidentTypes = $incCtrl->getIncidentsTypes();
		$incidents = $this->getListIncidents($incidentsAll, $incidentTypes);

		return response()
			->json([
				'status' => 'success',
				'incidents' => $incidents
			], 200);
	}

	public function getCentersIncidentsAreas() {
		$user = Auth::user();

		if (!is_null($user)) {
			$incCtrl = new IncidentCtrl();
			$incidentCenters = $incCtrl->getMapCenters();

			return response()
				->json([
					'status' => 'success',
					'centers' => $incidentCenters
				], 200);
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 401);
	}

	/**
	 * Devuelve los incidents subidos por un usuario
	 *
	 * @return JsonResponse
	 */
	public function getUploadedIncidentsByUser() {
		$user = Auth::user();

		if(!is_null($user)) {
			$incCtrl = new IncidentCtrl();
			$uploaded = $incCtrl->getUploadedIncidents($user['id']);
			$incidents = $uploaded['incidents']->get();
			$date_upload = $uploaded['date_upload'];

			$incidentTypes = $incCtrl->getIncidentsTypes();
			$incidentsUp = $this->getListIncidents($incidents, $incidentTypes, $date_upload);

			return response()
				->json([
					'status' => 'success',
					'incidents' => $incidentsUp
				], 200);
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 401);
	}

	/**
	 * Devuelve los tipos de delitos existentes
	 *
	 * @return JsonResponse
	 */
	public function getDelitos() {
		$incCtrl = new IncidentCtrl();
		$incidentTypes = $incCtrl->getIncidentsTypes();

		return response()
			->json([
				'status' => 'success',
				'delitos' => $incidentTypes
			], 200);
	}

	public function store(Request $request) {
		$user = Auth::user();

		if (!is_null($user)) {
			$datos = $request->validate([
				'delito' => 'bail|required',
				'fecha_hora_incidente' => 'bail|required|date|before:tomorrow',
				'lugar' => 'bail|required',
				'nombre_lugar' => 'required',
				'descripcion_incidente' => 'bail|required|min:10|max:1000',
				'afectado_testigo' => 'bail|required|boolean'
			]);

			$lastId = Incidente::orderBy('id', 'desc')->value('id');
			$lat_long_site = explode(",", $datos['lugar']);

			$incInput = array(
				'id' => $lastId + 1,
				'delito_id' => $datos['delito'],
				'latitud_incidente' => $lat_long_site[0],
				'longitud_incidente' => $lat_long_site[1],
				'nombre_lugar' => $datos['nombre_lugar'],
				'fecha_hora_incidente' => $datos['fecha_hora_incidente'],
				'descripcion_incidente' => $datos['descripcion_incidente'],
				'afectado_testigo' => $datos['afectado_testigo'],
				'agravantes' => $request['agravantes']
			);

			$upInput = array(
				'usuario_id' => $user['id'],
				'delito_id' => $datos['delito'],
				'incidente_id' => $lastId + 1
			);

			Incidente::create($incInput);
			Suben::create($upInput);

			$notifCtrl = new UserNotificationsController();
			$notifCtrl->notifyNewIncident($incInput);

			$incCtrl = new IncidentCtrl();
			$incCtrl->publishIncidentTwitter($incInput);

			return response()
				->json([
					'status' => 'success',
					'datos' => $datos,
					'upInput' => $upInput
				], 200);
		}
		return response()
			->json([
				'status' => 'error',
				'message' => '¡El usuario no existe!'
			], 401);
	}

	public function test(Request $request) {
		dd($request);
	}
}
