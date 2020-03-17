<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delitos', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('nombre_delito')->unique();
			$table->string('categoria_delito');
			$table->string('descripcion_delito')->nullable();
			$table->smallInteger('pena_min')->nullable();
			$table->smallInteger('pena_max')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delitos');
    }
}
