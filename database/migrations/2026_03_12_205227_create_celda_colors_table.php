<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('celda_colors', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fila');
            $table->string('columna');
            $table->string('color', 20);
            $table->string('tabla')->nullable();      // opcional: nombre de la tabla
            $table->unsignedBigInteger('id_infoUsuario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celda_colors');
    }
};
