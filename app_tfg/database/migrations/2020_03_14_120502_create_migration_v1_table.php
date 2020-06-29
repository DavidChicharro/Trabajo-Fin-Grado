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
			$table->string('api_token')->unique();
        });

		Schema::create('delitos', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('nombre_delito')->unique();
			$table->string('categoria_delito');
			$table->string('descripcion_delito')->nullable();
			$table->smallInteger('pena_min')->nullable();
			$table->smallInteger('pena_max')->nullable();
			$table->string('color',6)->nullable();
		});

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
			$table->string('agravantes')->nullable();
			$table->decimal('nivel_gravedad', 10, 8)->nullable();
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

		Schema::create('usuarios_zonas_interes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('usuario_id');
			$table->decimal('latitud_zona_interes',8,4);
			$table->decimal('longitud_zona_interes',8,4);
			$table->string('nombre_zona_interes')->nullable();
			$table->integer('radio_zona_interes');
			$table->foreign('usuario_id')->references('id')->on('users');
		});

		Schema::create('notifications', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('type');
			$table->morphs('notifiable');
			$table->text('data');
			$table->timestamp('read_at')->nullable();
			$table->timestamps();
		});

		Schema::create('incidents_areas_centers', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->decimal('lat_center',9,6);
			$table->decimal('lng_center',9,6);
			$table->decimal('severity_level',9,6);
			$table->string('color',6)->nullable();
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
		Schema::dropIfExists('users');
		Schema::dropIfExists('delitos');
		Schema::dropIfExists('incidentes');
		Schema::dropIfExists('suben');
		Schema::dropIfExists('son_contactos_favoritos');
		Schema::dropIfExists('usuarios_zonas_interes');
		Schema::dropIfExists('notifications');
		Schema::dropIfExists('incidents_areas_centers');
    }
}
