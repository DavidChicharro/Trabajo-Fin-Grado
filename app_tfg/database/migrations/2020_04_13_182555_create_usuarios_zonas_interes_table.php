<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosZonasInteresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_zonas_interes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('usuario_id');
			$table->decimal('latitud_zona_interes',8,4);
			$table->decimal('longitud_zona_interes',8,4);
			$table->string('nombre_zona_interes')->nullable();
			$table->integer('radio_zona_interes');
			$table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zonas_interes');
    }
}
