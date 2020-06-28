<?php

namespace App\Http\Controllers;

use App\Delito;
use App\IncidentAreaCenter;
use App\Incidente;
use App\Suben;
use Illuminate\Http\Request;
use App\User;
use Abraham\TwitterOAuth\TwitterOAuth;

class IncidentsController extends Controller {
	private $numPags = 10;

	public function calcDistance($latitudeFrom, $longitudeFrom,
	  $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$lonDelta = $lonTo - $lonFrom;
		$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		$angle = atan2(sqrt($a), $b);

		return $angle * $earthRadius;
	}

	public function getIncidentsTypes() {
		$groupIncidents = Delito::all()->groupBy('id')->toArray();
		$incidentTypes = array();
		foreach ($groupIncidents as $id => $incident){
			$incidentTypes[$id] = $incident[0]['nombre_delito'];
		}

		return $incidentTypes;
	}

	private function getListIncidents($incidents_pag, $incidentTypes, $date_upload=null) {
		if ($incidents_pag->total() != 0) {
			foreach ($incidents_pag->items() as $key => $inc) {
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

	public function mapaIncidentes(Request $request) {
		$session = session('email');

		if (isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$groupIncidents = Delito::all()->groupBy('id')->toArray();
			$incidentTypes = array();
			foreach ($groupIncidents as $id => $incident){
				$incidentTypes[$id] = $incident[0]['nombre_delito'];
			}

			$result = compact(['username', 'notifications', 'incidentTypes']);
			return view('incidents.map', $result);
		}
		return redirect()->route('index');
	}

	public function getMapCenters() {
		return IncidentAreaCenter::all()->toArray();
	}

	public function getMapIncidents(Request $request) {
		$req_date = !is_null($request['dateFrom']) && !is_null($request['dateTo']);
		$req_type = !is_null($request['delitTypes']);
		$appliedFilter = [];

		if ($req_date || $req_type) {
			$range_id_delito = $req_type ? $request['delitTypes'] : null;
			$range_date_delito = $req_date ? [$request['dateFrom'], $request['dateTo']] : null;

			if ($req_date && $req_type) {
				$allIncidents = Incidente::whereIn('delito_id', $range_id_delito)
					->whereBetween('fecha_hora_incidente',$range_date_delito)
					->where('oculto', 0)->where('caducado', 0)->get();

				$appliedFilter = ["delitos" => $range_id_delito, "rango" => $range_date_delito];
			} elseif ($req_date && !$req_type) {
				$allIncidents = Incidente::whereBetween('fecha_hora_incidente', $range_date_delito)
					->where('oculto', 0)->where('caducado', 0)->get();

				$appliedFilter = ["rango" => $range_date_delito];
			} else {
				$allIncidents = Incidente::whereIn('delito_id', $range_id_delito)
					->where('oculto', 0)->where('caducado', 0)->get();

				$appliedFilter = ["delitos" => $range_id_delito];
			}
		} else {
			$allIncidents = Incidente::where('oculto', 0)->where('caducado', 0)->get();
		}

		if (!is_null($request['mapLimits'])) {
			// Se añade un margen de 0.5º para cargar incidentes de alrededor de la vista
			$westLimit = floatval($request['mapLimits'][0])-0.5;
			$southLimit = floatval($request['mapLimits'][1])-0.5;
			$eastLimit = floatval($request['mapLimits'][2])+0.5;
			$northLimit = floatval($request['mapLimits'][3])+0.5;

			$incidentTypes = $this->getIncidentsTypes();

			$incidents = [];
			foreach ($allIncidents as $key => $incident) {
				$latInc = floatval($incident['latitud_incidente']);
				$longInc = floatval($incident['longitud_incidente']);

				if ($latInc < $northLimit && $latInc > $southLimit &&
				$longInc < $eastLimit && $longInc > $westLimit) {
					$incidents[$key]['id'] = $incident['id'];
					$incidents[$key]['incidente'] = ucfirst($incidentTypes[$incident['delito_id']]);
					$incidents[$key]['latitud'] = $latInc;
					$incidents[$key]['longitud'] = $longInc;
					$incidents[$key]['fecha_hora'] = $incident['fecha_hora_incidente'];
					$incidents[$key]['descripcion'] = $incident['descripcion_incidente'];
					$incidents[$key]['nombre_lugar'] = $incident['nombre_lugar'];
				}
			}
			$centers = $this->getMapCenters();
			$result = [
				"incidents" => $incidents,
				"centers" => $centers,
				"appliedFilter" => $appliedFilter,
				"incTypes" => $incidentTypes
			];
			return json_encode($result);
		}
		return null;
	}

	public function listaIncidentes(Request $request) {
		$session = session('email');

		if (isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;
			$appliedFilter = [];

			$req_date = $request['desde']!=null && $request['hasta']!=null;
			$req_type = $request['tipos_incidentes']!=null;

			if($req_date || $req_type){
				$range_id_delito = $req_type ? $request['tipos_incidentes'] : null;
				$range_date_delito = $req_date ? [$request['desde'], $request['hasta']] : null;

				if ($req_date && $req_type) {
					$incidents_pag = Incidente::whereIn('delito_id', $range_id_delito)
						->whereBetween('fecha_hora_incidente',$range_date_delito)
						->where('oculto', 0)->where('caducado', 0)
						->orderBy('fecha_hora_incidente', 'desc')
						->paginate($this->numPags);
					$appliedFilter = ["delitos" => $range_id_delito, "rango" => $range_date_delito];
				} elseif ($req_date && !$req_type) {
					$incidents_pag = Incidente::whereBetween('fecha_hora_incidente', $range_date_delito)
						->where('oculto', 0)->where('caducado', 0)
						->orderBy('fecha_hora_incidente', 'desc')
						->paginate($this->numPags);
					$appliedFilter = ["rango" => $range_date_delito];
				} else {
					$incidents_pag = Incidente::whereIn('delito_id',$range_id_delito)
						->where('oculto', 0)->where('caducado', 0)
						->orderBy('fecha_hora_incidente', 'desc')
						->paginate($this->numPags);
					$appliedFilter = ["delitos" => $range_id_delito];
				}
			} else {
				$incidents_pag = Incidente::where('oculto', 0)->where('caducado', 0)
					->orderBy('fecha_hora_incidente', 'desc')
					->paginate($this->numPags);
			}

			$incidentTypes = $this->getIncidentsTypes();
			$incidents = $this->getListIncidents($incidents_pag, $incidentTypes);

			$result = compact(['username','incidents','incidents_pag','incidentTypes', 'notifications', 'appliedFilter']);
			return view('incidents.list', $result);
		}
		return redirect()->route('index');
	}

	public function getIncidentDetails(Request $request) {
		if (isset($request['incidentId'])) {
			$inc = Incidente::where('id', $request['incidentId'])->first();

			$incidente['descripcion'] = $inc['descripcion_incidente'];

			return response()->json(array('incidente'=>$incidente), 200);
		}
		return response()->json(array('msg'=>'error'), 404);
	}

	public function getIncident($incId, $delId) {
		if (!is_null($incId) && !is_null($delId)) {
			$incident = Incidente::where('id', $incId)
				->where('delito_id', $delId)
				->first();

			return response()
				->json([
					'incident' => $incident
				], 200);
		}
		return response()
			->json([
				'status' => 'error'
			], 400);
	}

	public function incidente(Request $request) {
		if (isset($request['inc']) && isset($request['del'])) {
			$incident = json_decode(
				$this->getIncident($request['inc'], $request['del'])->getContent(),
				true
			)['incident'];

			if (!is_null($incident)) {
				$del = Delito::where('id', $request['del'])->value('nombre_delito');
				$result = compact(['incident', 'del']);

				return view('incidents.incident', $result);
			}
		}
		abort(404);
	}

	public function nuevoIncidente(Request $request) {
		$session = session('email');

		if (isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$delitos = array_keys(Delito::all()->groupBy('categoria_delito')->toArray());

			$result = compact(['delitos', 'username', 'notifications']);
			return view('incidents.new-incident', $result);
		}
		return redirect()->route('index');
	}

	public function store(Request $request) {
		$session = session('email');

		if (isset($session)) {
			$datos = $request->validate([
				'delito' => 'bail|required',
				'fecha_incidente' => 'bail|required|date|before:tomorrow',
				'hora_incidente' => 'bail|required',
				'lugar' => 'bail|required',
				'nombre_lugar' => 'required',
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
				'nombre_lugar' => $datos['nombre_lugar'],
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

			$notifCtrl = new UserNotificationsController();
			$notifCtrl->notifyNewIncident($incInput);

			$this->publishIncidentTwitter($incInput);

			return redirect()
				->route('mapaIncidentes')
				->with('message', 'Incidente registrado');
		}
		return redirect()->route('index');
	}

	public function getDelitos(Request $request) {
		if (isset($request['delitos'])) {
			return Delito::whereIn('categoria_delito', $request['delitos'])->get();
		}
		return null;
	}

	public function getUploadedIncidents($userId) {
		$uploaded = Suben::all()->where('usuario_id', $userId);

		$range_incidents = array();
		$date_upload = array();
		foreach ($uploaded as $up) {
			array_push($range_incidents, $up['incidente_id']);
			$date_upload[$up['incidente_id']] = $up['fecha_hora_sube_incidente'];
		}

		$incidents = Incidente::whereIn('id', $range_incidents);
//			->paginate($this->numPags);

		return compact(['incidents', 'date_upload']);
	}

	public function incidentesSubidos(Request $request) {
		$session = session('email');

		if(isset($session)) {
			$user = User::where('email', $session)->first();
			$username = $user['nombre'];
			$notifications = $user->unreadNotifications;

			$uploaded = $this->getUploadedIncidents($user['id']);
			$incidents_pag = $uploaded['incidents']->paginate($this->numPags);
			$date_upload = $uploaded['date_upload'];

			$incidentTypes = $this->getIncidentsTypes();
			$incidents = $this->getListIncidents($incidents_pag, $incidentTypes, $date_upload);

			$result = compact(['username', 'incidents', 'incidents_pag', 'notifications']);
			return view('incidents.uploaded', $result);
		}
		return redirect()->route('index');
	}

	/**
	 * Devuelve un delito según el identificador del propio delito
	 * @param $offences
	 * @param $id
	 * @return int
	 */
	private function getOffenceById($offences, $id) {
		foreach ($offences as $key => $offence) {
			if ($offence['id'] == $id) {
				return $key;
			}
		}
		return -1;
	}

	/**
	 * Normaliza un valor en función de un intervalo
	 *
	 * @param $x
	 * @param $min_x
	 * @param $max_x
	 * @return float|int
	 */
	private function normalize($x, $min_x, $max_x) {
		return ($x - $min_x) / ($max_x - $min_x);
	}

	/**
	 * Calcula el nivel de gravedad de un incidente
	 *
	 * @param $sumNeighSevLvl
	 * @param $numNeighIncs
	 * @return float|int
	 */
	private function calcNeighboursSeverityLevel($sumNeighSevLvl, $numNeighIncs) {
		return (1 / sqrt($numNeighIncs)) * $sumNeighSevLvl;
	}

	/**
	 * Establece el valor del nivel de gravedad de los incidentes y devuelve un array
	 * con dicho valor asignado
	 *
	 * @param $incidents
	 * @param $neighIncidents
	 * @param $offences
	 * @param $minDBPenalty
	 * @param $maxDBPenalty
	 * @return array
	 */
	private function setSeverityLevel($incidents, $neighIncidents, $offences, $minDBPenalty, $maxDBPenalty) {
		$incidentsWithSeverityLevel = array();
		foreach ($incidents as $incident) {
			$offenceId = $this->getOffenceById($offences, $incident['delito_id']);
			$offence = $offences[$offenceId];
			$minPenalty = $basePenalty = $offence['pena_min'];
			$maxPenalty = $offence['pena_max'];
			$diffPenalty = $maxPenalty - $minPenalty;

			$aggravs = explode(',', $incident['agravantes']);
			$aggravs_accum = 0.0;
			if (!empty($aggravs)) {
				if (in_array('1', $aggravs))	// disfraz
					$aggravs_accum += 0.175;
				if (in_array('2', $aggravs))	// abuso de superioridad
					$aggravs_accum += 0.225;
				if (in_array('3', $aggravs))	// sufrimiento inhumano
					$aggravs_accum += 0.3;
				if (in_array('4', $aggravs))	// Discriminación
					$aggravs_accum += 0.3;
			}

			if ($aggravs_accum > 0.0)
				$basePenalty += $diffPenalty * $aggravs_accum;

			$ownSevLvl = $this->normalize($basePenalty, $minDBPenalty, $maxDBPenalty);

			$sumNeighSevLvl = 0.0;
			$numNeighIncs = 0;
			foreach($neighIncidents as $neighInc) {
				if ($incident['id'] != $neighInc['id']) {
					$latFrom = $incident['latitud_incidente'];
					$lngFrom = $incident['longitud_incidente'];
					$latTo= $neighInc['latitud_incidente'];
					$lngTo = $neighInc['longitud_incidente'];
					if ($this->calcDistance($latFrom, $lngFrom, $latTo, $lngTo) < 550) {
						$sumNeighSevLvl += $neighInc['nivel_gravedad'];
						$numNeighIncs++;
					}
				}
			}

			$neighSevLvl = $numNeighIncs > 0 ?
				$this->calcNeighboursSeverityLevel($sumNeighSevLvl, $numNeighIncs) : 0.0;
			$incidentSeverityLevel = 0.6 * $ownSevLvl + 0.4 * $neighSevLvl;
			/** ** AÑADIR INCIDENCIA TEMPORAL ** **/
			$incident['nivel_gravedad'] = $incidentSeverityLevel;
			array_push($incidentsWithSeverityLevel, $incident);
		}

		return $incidentsWithSeverityLevel;
	}

	/**
	 * Calcula el nivel de gravedad de cada incidente. En primer lugar se realiza el cálculo
	 * para los nuevos incidentes y a continuación para los ya existentes que se encuentran
	 * cercanos a los nuevos
	 */
	public function calcIncidentsSeverityLevel() {
		$offences = Delito::all()->toArray();
		$minPenalty = 0;
		$maxPenalty = 150;

		$incidentsOld = Incidente::whereNotNull('nivel_gravedad')
			->where('oculto', 0)
			->where('caducado', 0)
			->get()
			->toArray();
		$incidentsNew = Incidente::whereNull('nivel_gravedad')
			->get()
			->toArray();

		// Cálculo del nivel de gravedad de los incidentes nuevos
		if (!empty($incidentsNew)) {
			$newIncsWithLvl = $this->setSeverityLevel($incidentsNew, $incidentsOld, $offences, $minPenalty, $maxPenalty);

			if (!empty($newIncsWithLvl)) {
				foreach ($newIncsWithLvl as $newInc) {
					$upIncident = Incidente::where('id', $newInc['id'])
						->where('delito_id', $newInc['delito_id'])
						->first();
					$upIncident->fill($newInc)->save();
				}

				$allIncidents = array_merge($incidentsOld, $newIncsWithLvl);
				// Cálculo del nivel de gravedad de los incidentes ya existentes si hay nuevos
				$allIncsWithLvl = $this->setSeverityLevel($allIncidents, $allIncidents, $offences, $minPenalty, $maxPenalty);

				if (!empty($allIncsWithLvl)) {
					foreach ($allIncsWithLvl as $incident) {
						$upldIncident = Incidente::where('id', $incident['id'])
							->where('delito_id', $incident['delito_id'])
							->first();
						if ($upldIncident['nivel_gravedad'] != $incident['nivel_gravedad'])
							$upldIncident->fill($incident)->save();
					}
				}
				return "Calculado nivel de gravedad de los nuevos incidentes";
			}
		}

		return "No hay nuevos incidentes";
	}


	/**
	 * Obtiene los datos del fichero CSV que almacena la posición de los centros
	 * de las áreas de con incidentes junto con el nivel de gravedad de la misma
	 *
	 * @return array
	 */
	public function getCenters() {
		$path = str_replace('app_tfg', 'files', base_path());
		$file = '/centers_' . date('Y-m-d') . '.csv';
		$filename = $path.$file;

		if (file_exists($filename)) {
			$fileHandle = fopen($filename, "r");
			$centersList = array();
			while (!feof($fileHandle)) {
				$line = fgetcsv($fileHandle, 0, ',');
				if ($line)
					array_push($centersList, $line);
			}

			return $centersList;
		}

		return [];
	}

	/**
	 * Obtiene el color asociado según su valor en un intervalo
	 *
	 * @param $minColor
	 * @param $maxColor
	 * @param $normValue
	 * @return string
	 */
	public function getColor($minColor, $maxColor, $normValue) {
		$difColor = $maxColor - $minColor;
		$valColor = $minColor + ($difColor * $normValue);
		return ($valColor < 16) ? '0'.dechex($valColor) : dechex($valColor);
	}

	/**
	 * Calcula a partir de los centros de las zonas con incidentes,
	 * según su nivel de gravedad asociado, el color que le corresponde
	 * en una escala que refleja el nivel de gravedad de forma visual
	 *
	 * @param $centers
	 * @return array
	 */
	public function getCentersByGroup($centers) {
		$centersWithColor = array();
		$minLevel = floatval(min(array_column($centers, '2')));
		$maxLevel = floatval(max(array_column($centers, '2')));

		foreach ($centers as $center) {
			$normCenter = $this->normalize($center[2], $minLevel, $maxLevel);
			if ($normCenter >= 0.0 && $normCenter < 0.2) {
				$normInterval = $this->normalize($normCenter, 0.0, 0.2);
				$hexColor = $this->getColor(hexdec('80'), hexdec('FF'), $normInterval);
				$hexColor2 = $this->getColor(hexdec('B0'), hexdec('F0'), $normInterval);
				$defColor = $hexColor.$hexColor2."00";
			} elseif ($normCenter >= 0.2 && $normCenter < 0.4) {
				$normInterval = $this->normalize($normCenter, 0.2, 0.4);
				$hexColor = $this->getColor(hexdec('F0'), hexdec('80'), $normInterval);
				$defColor = "FF".$hexColor."00";
			} elseif ($normCenter >= 0.4 && $normCenter < 0.6) {
				$normInterval = $this->normalize($normCenter, 0.4, 0.6);
				$hexColor = $this->getColor(hexdec('80'), hexdec('00'), $normInterval);
				$defColor = "FF".$hexColor."00";
			} elseif ($normCenter >= 0.6 && $normCenter < 0.8) {
				$normInterval = $this->normalize($normCenter, 0.6, 0.8);
				$hexColor = $this->getColor(hexdec('FF'), hexdec('80'), $normInterval);
				$defColor = $hexColor."0000";
			} else {
				$normInterval = $this->normalize($normCenter, 0.8, 1.0);
				$hexColor = $this->getColor(hexdec('80'), hexdec('00'), $normInterval);
				$defColor = $hexColor."0000";
			}

			$center['color'] = $defColor;
			array_push($centersWithColor, $center);
		}

		return $centersWithColor;
	}

	/**
	 * Inserta en la base de datos los centros de las zonas con incidentes
	 * junto con su nivel de gravedad y el color
	 */
	public function setCentersSeverityLevel() {
		$centers = $this->getCenters();
		$centersWithColor = $this->getCentersByGroup($centers);

		$data = array();
		foreach ($centersWithColor as $center) {
			array_push($data,
				array(
					'lat_center' => $center[0],
					'lng_center' => $center[1],
					'severity_level' => $center[2],
					'color' => $center['color']
				)
			);
		}
		if (!empty($data)) {
			IncidentAreaCenter::truncate();
			IncidentAreaCenter::insert($data);
		}
	}

	/**
	 * Publica un tweet con los datos del incidente
	 *
	 * @param $incident
	 */
	public function publishIncidentTwitter($incident) {
		$tweetLength = 280;
		$urlLenght = 35;
		$connection = new TwitterOAuth(
			getenv('CONSUMER_KEY'),
			getenv('CONSUMER_SECRET'),
			getenv('ACCESS_TOKEN'),
			getenv('ACCESS_TOKEN_SECRET')
		);

		$datetime = date("d/m/Y H:i", strtotime($incident['fecha_hora_incidente']));
		$spltDT = explode(' ', $datetime);
		$del = Delito::where('id', $incident['delito_id'])->first();

		$text = 'Nuevo incidente: ' . $del['nombre_delito'] . chr(13) . chr(10) . chr(13) . chr(10) .
			'En ' . $incident['nombre_lugar'] . ' el ' . $spltDT[0] . ' a las ' . $spltDT[1] .
			chr(13) . chr(10);

		$length = strlen($text) + strlen($incident['descripcion_incidente']);
		if ($length + $urlLenght > $tweetLength) {
			$maxDescLength = $tweetLength - $length - $urlLenght;
			$description = substr($incident['descripcion_incidente'], 0, $maxDescLength);
		} else {
			$description = $incident['descripcion_incidente'];
		}

		$status =  $text . $description . chr(13) . chr(10) . chr(13) . chr(10) .
			'https://www.kifungo.live/incidente?inc='. $incident['id'] .'&del=' . $incident['delito_id'];

		$post_tweets = $connection->post("statuses/update", [
			"status" => $status,
			"lat" => $incident['latitud_incidente'],
			"long" => $incident['longitud_incidente']
		]);
	}
}
