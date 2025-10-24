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
        Schema::create('notas', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->unsignedBigInteger('id_procedimiento')->nullable();
            $table->unsignedBigInteger('id_profesional')->nullable();
            $table->text('direccion');
            $table->date('fecha_nota');
            $table->time('hora_nota');
            $table->text('nota');
            $table->text('tipoAnalisis');
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
        Schema::dropIfExists('notas');
    }
};
