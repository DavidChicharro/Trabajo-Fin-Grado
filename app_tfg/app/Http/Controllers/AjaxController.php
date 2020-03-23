<?php

namespace App\Http\Controllers;

use App\Delito;
use Illuminate\Http\Request;
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

	public function getConfigParams(Request $request){
    	$modalParams = null;
		if(isset($request['modal'])) {
			switch ($request['modal']) {
				case 'caduc':
					$modalParams = $this->getParams('caducidad_incidentes');
					break;
				case 'contact-fav':
					$modalParams = $this->getParams('contactos_favoritos');
					break;
				case 'zona-int':
					$modalParams = $this->getParams('zonas_interes');
					break;
				default:
					break;
			}
		}
		return $modalParams;

//			config(['api.caducidad_incidentes.radio' => '30']);
//			$val = config('api.caducidad_incidentes.radio');
//			dd($val);
	}

	private function setParams($key, $params){
    	foreach ($params as $param => $value)
    		config(['api.'.$key.'.'.$param => $value]);
	}

	public function setConfigParams(Request $request){

	}
}
