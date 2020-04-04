<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactosFavoritos extends Model
{
	protected $table = 'son_contactos_favoritos';

	public $timestamps = false;

	protected $fillable = [
		'usuario_id', 'contacto_favorito_id', 'son_contactos', 'contador', 'orden'
	];
}