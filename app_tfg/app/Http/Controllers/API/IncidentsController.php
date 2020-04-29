<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IncidentsController as IncidentCtrl;
use App\Incidente;
use Illuminate\Http\Request;

class IncidentsController extends Controller
{
	private function getListIncidents($incidentsAll, $incidentTypes, $date_upload=null) {
		if(!empty($incidentsAll)) {
			foreach ($incidentsAll as $key => $inc) {
				$incidents[$key]['id'] = $inc['id'];
				$incidents[$key]['incidente'] = $incidentTypes[$inc['delito_id']];
				$incidents[$key]['lugar'] = $inc['latitud_incidente'] . ', ' . $inc['longitud_incidente'];
				$incidents[$key]['fecha_hora'] = $inc['fecha_hora_incidente'];
				$incidents[$key]['nombre_lugar'] = $inc['nombre_lugar'];
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
				$appliedFilter = ["delitos" => $range_id_delito, "rango" => $range_date_delito];
			}elseif ($req_date && !$req_type){
				$incidentsAll = Incidente::whereBetween('fecha_hora_incidente',$range_date_delito)
					->where('oculto', 0)->where('caducado', 0)
					->orderBy('fecha_hora_incidente', 'desc')
					->get();
				$appliedFilter = ["rango" => $range_date_delito];
			}else{
				$incidentsAll = Incidente::whereIn('delito_id',$range_id_delito)
					->where('oculto', 0)->where('caducado', 0)
					->orderBy('fecha_hora_incidente', 'desc')
					->get();
				$appliedFilter = ["delitos" => $range_id_delito];
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

//		$result = compact(['username','incidents','incidents_pag','incidentTypes', 'notifications', 'appliedFilter']);
//		return view('incidents.list', $result);
	}
}
