<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationV1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nombre');
            $table->string('apellidos')->nullable();
            $table->string('dni',10)->unique();
            $table->dateTime('fecha_nacimiento',0)->nullable();
            $table->dateTime('fecha_alta',0)->useCurrent()->nullable();
            $table->tinyInteger('es_admin')->default(0);
            $table->string('telefono',9)->unique();
            $table->string('telefono_fijo',9)->nullable();
            $table->string('pin_secreto',8)->nullable();
            $table->string('accion_panico')->nullable();
            $table->decimal('latitud_actual',8,4)->nullable();
            $table->decimal('longitud_actual',8,4)->nullable();
            $table->rememberToken();
        });

		Schema::create('delitos', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('nombre_delito')->unique();
			$table->string('categoria_delito');
			$table->string('descripcion_delito')->nullable();
			$table->smallInteger('pena_min')->nullable();
			$table->smallInteger('pena_max')->nullable();
		});

		Schema::create('incidentes', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->unsignedBigInteger('id');
			$table->unsignedBigInteger('delito_id');
			$table->decimal('latitud_incidente',8,4);
			$table->decimal('longitud_incidente',8,4);
			$table->datetime('fecha_hora_incidente');
			$table->tinyInteger('afectado_testigo');
			$table->string('agravantes')->nullable();
			$table->tinyInteger('nivel_gravedad')->nullable();
			$table->tinyInteger('oculto')->default(0);
			$table->tinyInteger('caducado')->default(0);
			$table->foreign('delito_id')->references('id')->on('delitos');
			$table->primary(['delito_id','id']);
		});
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
        Schema::dropIfExists('migration_v1');
    }
}
