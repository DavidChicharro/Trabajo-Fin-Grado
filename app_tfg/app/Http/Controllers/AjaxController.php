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
}
