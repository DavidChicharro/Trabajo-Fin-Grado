<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentAreaCenter extends Model
{
	protected $table = 'incidents_areas_centers';

	public $timestamps = false;

	protected $fillable = [
		'id',
		'lat_center',
		'lng_center',
		'severity_level',
		'color'
	];
}
