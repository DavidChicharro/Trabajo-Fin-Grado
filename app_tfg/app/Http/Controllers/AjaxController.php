<?php

namespace App\Http\Controllers;

use App\Delito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function index(Request $request){
//    	$d = Delito::orderBy('id','desc')->first();
//		DB::select("")
//		dd($request);
		$input = $request->all();
		$incident_id = $input['incidentId'];



    	$var = "Estoy en el index del controlador de AJAX";
    	return response()->json(array('msg'=>$var, 'request'=>$incident_id), 200);
//    	return "Estoy en el index del controlador de AJAX";
//    	dd($var);
//    	echo $var;
	}

	private function getParams($key){
		return config(('api.'.$key));
	}

	public function getZonasInteresParms(){
    	return $this->getParams('zonas_interes');
	}

	public function getConfigParams(Request $request){
    	$modalParams = null;
		if(isset($request['params'])) {
			switch ($request['params']) {
				case 'caduc':
					$modalParams = $this->getParams('caducidad_incidentes');
					break;
				case 'cfav':
					$modalParams = $this->getParams('contactos_favoritos');
					break;
				case 'zint':
					$modalParams = $this->getParams('zonas_interes');
					break;
				default:
					break;
			}
		}
		return $modalParams;
	}

	private function setParams($key, $params){
    	foreach ($params as $param => $value) {
			config(['api.' . $key . '.' . $param => $value]);
		}

		$fp = fopen(base_path().'/config/api.php','w');
		fwrite($fp, '<?php return '.var_export(config('api'), true).';');
	}

	public function setConfigParams(Request $request){
    	if(isset($request['configId']) and !empty($request['values'])){
    		$key = "";
    		switch ($request['configId']){
				case 'caduc':
					$key = 'caducidad_incidentes';
					break;
				case 'cfav':
					$key = 'contactos_favoritos';
					break;
				case 'zint':
					$key = 'zonas_interes';
					break;
			}
			$this->setParams($key,$request['values']);
		}
	}

	public function getData(Request $request){
    	$data = array(
    		0 => array(
    			"nombre" => "David",
				"edad" => 22
			),
			1 => array(
				"nombre" => "Javier",
				"edad" => 21
			),
		);

		return response()->json($data);
	}
}
