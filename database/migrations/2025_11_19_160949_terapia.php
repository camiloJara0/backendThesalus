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
        Schema::create('terapia', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_procedimiento')->nullable();
            $table->unsignedBigInteger('id_profesional')->nullable();
            $table->text('objetivos');
            $table->date('fecha');
            $table->time('hora');
            $table->text('sesion');
            $table->text('evolucion');
            $table->timestamps();

            // Claves foráneas con ON DELETE SET NULL
            $table->foreign('id_paciente')
                  ->references('id')
                  ->on('pacientes')
                  ->onDelete('set null');

            $table->foreign('id_procedimiento')
                  ->references('id')
                  ->on('plan_manejo_procedimientos')
                  ->onDelete('set null');

            $table->foreign('id_profesional')
                  ->references('id')
                  ->on('profesionals')
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
        Schema::dropIfExists('terapia');
    }
};
