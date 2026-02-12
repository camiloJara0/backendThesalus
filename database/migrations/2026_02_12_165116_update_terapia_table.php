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
        DB::statement("ALTER TABLE terapia DROP FOREIGN KEY terapia_id_profesional_foreign");
        DB::statement("ALTER TABLE terapia DROP FOREIGN KEY terapia_id_paciente_foreign");

        // eliminar las columnas
        DB::statement("ALTER TABLE terapia DROP COLUMN id_profesional");
        DB::statement("ALTER TABLE terapia DROP COLUMN id_paciente");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Volver a crear las columnas eliminadas
        DB::statement("ALTER TABLE terapia ADD COLUMN id_profesional BIGINT UNSIGNED AFTER id");
        DB::statement("ALTER TABLE terapia ADD COLUMN id_paciente BIGINT UNSIGNED AFTER id_profesional");

        // Restaurar las llaves foráneas
        DB::statement("ALTER TABLE terapia 
            ADD CONSTRAINT terapia_id_profesional_foreign FOREIGN KEY (id_profesional) REFERENCES profesionals(id)");
        DB::statement("ALTER TABLE terapia 
            ADD CONSTRAINT terapia_id_paciente_foreign FOREIGN KEY (id_paciente) REFERENCES pacientes(id)");
    }
};
