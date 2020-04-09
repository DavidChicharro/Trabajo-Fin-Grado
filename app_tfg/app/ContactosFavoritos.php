<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKey;

class ContactosFavoritos extends Model
{
	use HasCompositePrimaryKey;

	protected $table = 'son_contactos_favoritos';
	protected $primaryKey = ['usuario_id', 'contacto_favorito_id'];
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'usuario_id', 'contacto_favorito_id', 'son_contactos', 'contador', 'orden'
	];
}