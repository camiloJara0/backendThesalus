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
        Schema::create('citas', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_paciente');
            $table->unsignedBigInteger('id_medico');
            $table->unsignedBigInteger('id_examen_fisico')->nullable();
            $table->string('name_paciente', 100);
            $table->string('name_medico', 100);
            $table->string('servicio', 100);
            $table->text('motivo');
            $table->date('fecha');
            $table->time('hora');
            $table->string('estado', 20)->default('inactiva');
            $table->string('motivo_cancelacion', 100)->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('id_paciente')
                  ->references('id')
                  ->on('pacientes')
                  ->onDelete('cascade');

            $table->foreign('id_medico')
                  ->references('id')
                  ->on('profesionals')
                  ->onDelete('cascade');

            $table->foreign('id_examen_fisico')
                  ->references('id')
                  ->on('analises')
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
        Schema::dropIfExists('citas');
    }
};
