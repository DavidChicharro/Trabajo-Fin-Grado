<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function index(){
    	$var = "Estoy en el index del controlador de AJAX";
    	return response()->json(array('msg'=>$var), 200);
//    	return "Estoy en el index del controlador de AJAX";
//    	dd($var);
//    	echo $var;
	}
}
