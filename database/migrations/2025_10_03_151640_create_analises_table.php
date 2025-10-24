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
        Schema::create('analises', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_historia');
            $table->unsignedBigInteger('id_medico');
            $table->text('analisis');
            $table->text('observacion');
            $table->text('motivo');
            $table->string('tipoAnalisis', 100);
            $table->string('tratamiento', 100);
            $table->timestamps();

            // Claves foráneas con ON DELETE CASCADE
            $table->foreign('id_historia')
                  ->references('id')
                  ->on('historia__clinicas')
                  ->onDelete('cascade');

            $table->foreign('id_medico')
                  ->references('id')
                  ->on('profesionals')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analises');
    }
};
