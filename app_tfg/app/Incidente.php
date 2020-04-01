<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'id', 'delito_id', 'latitud_incidente', 'longitud_incidente', 'fecha_hora_incidente', 'descripcion_incidente', 'afectado_testigo', 'agravantes', 'nivel_gravedad', 'oculto', 'caducado'
	];

}
