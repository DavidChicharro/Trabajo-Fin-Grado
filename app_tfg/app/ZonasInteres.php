<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZonasInteres extends Model
{
	protected $table = 'usuarios_zonas_interes';

	public $timestamps = false;

	protected $fillable = [
		'id',
		'usuario_id',
		'latitud_zona_interes',
		'longitud_zona_interes',
		'nombre_zona_interes',
		'radio_zona_interes'
	];

}
