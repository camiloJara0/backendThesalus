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
        Schema::table('plan_manejo_medicamentos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_paciente')->nullable();
            $table->foreign('id_paciente')->references('id')->on('pacientes');

            $table->unsignedBigInteger('id_medico')->nullable();
            $table->foreign('id_medico')->references('id')->on('profesionals');
        });

        // Primero elimina la restricción de llave foránea
        DB::statement('ALTER TABLE plan_manejo_medicamentos DROP FOREIGN KEY plan_manejo_medicamentos_id_analisis_foreign');
        // Luego cambia la columna a nullable
        DB::statement('ALTER TABLE plan_manejo_medicamentos MODIFY id_analisis BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_manejo_medicamentos', function (Blueprint $table) {
            $table->dropForeign(['id_paciente']);
            $table->dropColumn('id_paciente');

            $table->dropForeign(['id_medico']);
            $table->dropColumn('id_medico');
        });

        // Revertir: volver a poner la columna como NOT NULL
        DB::statement('ALTER TABLE plan_manejo_medicamentos MODIFY id_analisis BIGINT UNSIGNED NOT NULL');

        // Restaurar la llave foránea
        DB::statement('ALTER TABLE plan_manejo_medicamentos 
            ADD CONSTRAINT plan_manejo_medicamentos_id_analisis_foreign 
            FOREIGN KEY (id_analisis) REFERENCES analises(id)');
    }
};
