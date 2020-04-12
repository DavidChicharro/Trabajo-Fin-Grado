<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIncidentesTable extends Migration
{
	private function nextId(){
		$statement = DB::select("SHOW TABLE STATUS LIKE 'delitos'");
		$nextId = $statement[0]->Auto_increment;

		return $nextId;
	}
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('incidentes', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->unsignedBigInteger('id');
			$table->unsignedBigInteger('delito_id');
			$table->decimal('latitud_incidente',8,4);
			$table->decimal('longitud_incidente',8,4);
			$table->string('nombre_lugar')->nullable();
			$table->datetime('fecha_hora_incidente');
			$table->text('descripcion_incidente');
			$table->tinyInteger('afectado_testigo');
			$table->string('agraventes')->nullable();
			$table->tinyInteger('nivel_gravedad')->nullable();
			$table->tinyInteger('oculto')->default(0);
			$table->tinyInteger('caducado')->default(0);
			$table->foreign('delito_id')->references('id')->on('delitos');
			$table->primary(['delito_id','id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidentes');
    }
}
