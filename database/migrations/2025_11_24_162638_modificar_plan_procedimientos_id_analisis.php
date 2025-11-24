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
        Schema::table('plan_manejo_procedimientos', function (Blueprint $table) {
                // 1. Eliminar la llave foránea
                $table->dropForeign(['id_analisis']);

                // 2. Eliminar la columna
                $table->dropColumn('id_analisis');

                // 3. Agregar la nueva columna
                $table->unsignedBigInteger('id_paciente')->nullable();

                // 4. Crear la nueva llave foránea
                $table->foreign('id_paciente')
                    ->references('id')
                    ->on('pacientes')
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
    Schema::table('plan_manejo_procedimientos', function (Blueprint $table) {
        // Revertir cambios: eliminar la nueva llave y columna
        $table->dropForeign(['id_paciente']);
        $table->dropColumn('id_paciente');

        // Restaurar la columna anterior
        $table->unsignedBigInteger('id_analisis')->nullable();
        $table->foreign('id_analisis')
              ->references('id')
              ->on('analisis')
              ->onDelete('cascade');
    });
    }
};
