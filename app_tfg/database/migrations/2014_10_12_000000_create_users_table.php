<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->tinyInteger('es_admin');
            $table->string('telefono',9)->unique();
            $table->string('telefono_fijo',9)->nullable();
            $table->string('pin_secreto',8)->nullable();
            $table->string('accion_panico')->nullable();
            $table->float('latitud_actual')->nullable();
            $table->float('longitud_actual')->nullable();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
