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
        // Crear tabla con id_historia unicos
        DB::statement("
            CREATE TEMPORARY TABLE paciente_historia_unica AS
            SELECT id_paciente, MIN(id) AS id_historia
            FROM historia__clinicas
            GROUP BY id_paciente;
        ");

        // Crear relacion en analisis con id_historia unico
        DB::statement("
            UPDATE analises a
            INNER JOIN historia__clinicas h
                ON a.id_historia = h.id
            INNER JOIN paciente_historia_unica ph
                ON h.id_paciente = ph.id_paciente
            SET a.id_historia = ph.id_historia
        ");

        // eliminar filas repetidas
        DB::statement("
            DELETE h1 FROM historia__clinicas h1
            INNER JOIN historia__clinicas h2 
            ON h1.id_paciente = h2.id_paciente 
            AND h1.id > h2.id
        ");

        // elimina la restricción de llave foránea
        DB::statement('ALTER TABLE historia__clinicas DROP FOREIGN KEY historia__clinicas_id_paciente_foreign');
        // cambia la columna a unique
        DB::statement('ALTER TABLE historia__clinicas MODIFY id_paciente BIGINT UNSIGNED NOT NULL UNIQUE');
        // crear relacion
        DB::statement("ALTER TABLE historia__clinicas ADD CONSTRAINT historia__clinicas_id_paciente_foreign FOREIGN KEY (id_paciente) REFERENCES pacientes(id)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. Eliminar la restricción UNIQUE
        DB::statement('ALTER TABLE historia__clinicas DROP INDEX id_paciente');

        // 2. Eliminar la llave foránea actual
        DB::statement('ALTER TABLE historia__clinicas DROP FOREIGN KEY historia__clinicas_id_paciente_foreign');

        // 3. Volver la columna a su estado anterior (sin UNIQUE)
        DB::statement('ALTER TABLE historia__clinicas MODIFY id_paciente BIGINT UNSIGNED NULL');

        // 4. Restaurar la llave foránea original
        DB::statement("
            ALTER TABLE historia__clinicas 
            ADD CONSTRAINT historia__clinicas_id_paciente_foreign 
            FOREIGN KEY (id_paciente) REFERENCES pacientes(id)
        ");
    }
};
