<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSonContactosFavoritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('son_contactos_favoritos', function (Blueprint $table) {
			$table->unsignedBigInteger('usuario_id');
			$table->unsignedBigInteger('contacto_favorito_id');
			$table->tinyInteger('son_contactos')->default(0);
			$table->tinyInteger('contador')->default(0);
			$table->smallInteger('orden')->default(99);
			$table->foreign('usuario_id')->references('id')->on('users');
			$table->foreign('contacto_favorito_id')->references('id')->on('users');
			$table->primary(['usuario_id','contacto_favorito_id']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('son_contactos_favoritos');
    }
}
