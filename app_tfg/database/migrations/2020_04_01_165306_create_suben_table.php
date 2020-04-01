<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suben', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id');
            $table->datetime('fecha_hora_sube_incidente')->useCurrent();
			$table->unsignedBigInteger('delito_id');
			$table->unsignedBigInteger('incidente_id');
			$table->foreign('usuario_id')->references('id')->on('users');
			$table->foreign(['delito_id','incidente_id'])->references(['delito_id','id'])->on('incidentes');
			$table->primary(['usuario_id','fecha_hora_sube_incidente']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suben');
    }
}
