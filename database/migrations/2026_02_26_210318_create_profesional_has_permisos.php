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
        Schema::create('profesional_has_permisos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_profesional');
            $table->unsignedBigInteger('id_seccion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->foreign('id_profesional')->references('id')->on('profesionals')->onDelete('cascade');
            $table->foreign('id_seccion')->references('id')->on('secciones')->onDelete('cascade');
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
        Schema::dropIfExists('profesional_has_permisos');
    }
};
