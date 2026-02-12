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
        // eliminar las llaves foráneas si existen
        DB::statement("ALTER TABLE notas DROP FOREIGN KEY fk_notas_profesional");
        DB::statement("ALTER TABLE notas DROP FOREIGN KEY fk_notas_procedimiento");
        DB::statement("ALTER TABLE notas DROP FOREIGN KEY notas_id_paciente_foreign");

        // eliminar las columnas
        DB::statement("ALTER TABLE notas DROP COLUMN id_profesional");
        DB::statement("ALTER TABLE notas DROP COLUMN id_paciente");
        DB::statement("ALTER TABLE notas DROP COLUMN id_procedimiento");
        DB::statement("ALTER TABLE notas DROP COLUMN nota");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Volver a crear las columnas eliminadas
        DB::statement("ALTER TABLE notas ADD COLUMN id_profesional BIGINT UNSIGNED AFTER id");
        DB::statement("ALTER TABLE notas ADD COLUMN id_procedimiento BIGINT UNSIGNED AFTER id_profesional");
        DB::statement("ALTER TABLE notas ADD COLUMN nota TEXT AFTER id_procedimiento");

        // Restaurar las llaves foráneas
        DB::statement("ALTER TABLE notas 
            ADD CONSTRAINT fk_notas_profesional FOREIGN KEY (id_profesional) REFERENCES profesionals(id)");
        DB::statement("ALTER TABLE notas 
            ADD CONSTRAINT fk_notas_procedimiento FOREIGN KEY (id_procedimiento) REFERENCES plan_manejo_procedimientos(id)");

    }
};
