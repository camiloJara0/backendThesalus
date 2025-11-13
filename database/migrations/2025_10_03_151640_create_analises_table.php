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
            $table->text('motivo');
            $table->text('analisis')->nullable();
            $table->text('observacion')->nullable();
            $table->string('tipoAnalisis', 100)->nullable();
            $table->string('tratamiento', 100)->nullable();
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
