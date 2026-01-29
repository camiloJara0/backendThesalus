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
        // Primero elimina la restricción de llave foránea
        DB::statement('ALTER TABLE terapia DROP FOREIGN KEY terapia_id_procedimiento_foreign');

        // Luego cambia la columna a nullable
        DB::statement('ALTER TABLE terapia MODIFY id_procedimiento BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        // Revertir: volver a poner la columna como NOT NULL
        DB::statement('ALTER TABLE terapia MODIFY id_procedimiento BIGINT UNSIGNED NOT NULL');

        // Restaurar la llave foránea
        DB::statement('ALTER TABLE terapia 
            ADD CONSTRAINT terapia_id_procedimiento_foreign 
            FOREIGN KEY (id_procedimiento) REFERENCES plan_manejo_procedimientos(id)');
    }

};
