<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suben extends Model
{
	protected $table = 'suben';

	public $timestamps = false;

	protected $fillable = [
		'usuario_id',
		'fecha_hora_sube_incidente',
		'delito_id',
		'incidente_id'
	];
}
