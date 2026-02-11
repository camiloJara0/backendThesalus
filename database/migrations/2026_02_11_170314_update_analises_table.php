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
        // Agregar nueva columna id_servicio
        DB::statement("ALTER TABLE analises ADD COLUMN id_servicio BIGINT UNSIGNED AFTER id_medico");

        // Migrar datos
        DB::statement("
            UPDATE analises a
            LEFT JOIN servicio s1 ON a.nombreServicio = s1.name
            LEFT JOIN servicio s2 ON a.servicio = s2.plantilla
            SET a.id_servicio = COALESCE(s1.id, s2.id)
        ");

        // Ahora sí eliminar columna servicio
        DB::statement("ALTER TABLE analises DROP COLUMN nombreServicio");
        DB::statement("ALTER TABLE analises DROP COLUMN servicio");

        // Crear relación con tabla servicios
        DB::statement("ALTER TABLE analises 
            ADD CONSTRAINT fk_analises_servicio FOREIGN KEY (id_servicio) REFERENCES servicio(id)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Volver a crear las columnas eliminadas
        DB::statement("ALTER TABLE analises ADD COLUMN nombreServicio VARCHAR(255) AFTER id_medico");
        DB::statement("ALTER TABLE analises ADD COLUMN servicio VARCHAR(255) AFTER nombreServicio");

        // Restaurar datos desde id_servicio
        DB::statement("
            UPDATE analises a
            INNER JOIN servicio s ON a.id_servicio = s.id
            SET a.nombreServicio = s.name,
                a.servicio = s.plantilla
        ");

        // Eliminar la columna id_servicio
        DB::statement("ALTER TABLE analises DROP COLUMN id_servicio");

    }
};
