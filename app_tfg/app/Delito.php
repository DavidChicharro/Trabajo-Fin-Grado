<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delito extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'id', 'nombre_delito', 'categoria_delito', 'descripcion_delito', 'pena_min', 'pena_max'
	];
}
