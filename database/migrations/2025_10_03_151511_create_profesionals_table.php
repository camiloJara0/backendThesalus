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
        Schema::create('profesionals', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_profesion')->nullable();
            $table->unsignedBigInteger('id_infoUsuario')->nullable();
            $table->string('zona_laboral', 50);
            $table->string('departamento_laboral', 50);
            $table->string('municipio_laboral', 50);
            $table->integer('estado')->default(1);
            $table->timestamps();

            // Claves foráneas con ON DELETE SET NULL
            $table->foreign('id_profesion')
                  ->references('id')
                  ->on('profesions')
                  ->onDelete('set null');

            $table->foreign('id_infoUsuario')
                  ->references('id')
                  ->on('informacion_users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profesionals');
    }
};
