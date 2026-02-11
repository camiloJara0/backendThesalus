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
        // Quitar la foreign key anterior
        DB::statement("ALTER TABLE citas DROP FOREIGN KEY citas_id_examen_fisico_foreign");

        // Renombrar columna
        DB::statement("ALTER TABLE citas CHANGE id_examen_fisico id_analisis BIGINT UNSIGNED");

        // Crear nueva foreign key hacia analises
        DB::statement("ALTER TABLE citas 
            ADD CONSTRAINT fk_citas_analisis FOREIGN KEY (id_analisis) REFERENCES analises(id) ON DELETE SET NULL");

        // Eliminar columnas innecesarias
        DB::statement("ALTER TABLE citas DROP COLUMN name_paciente");
        DB::statement("ALTER TABLE citas DROP COLUMN name_medico");

        // Agregar nueva columna id_servicio
        DB::statement("ALTER TABLE citas ADD COLUMN id_servicio BIGINT UNSIGNED AFTER id_analisis");

        // Migrar datos de servicio → id_servicio
        DB::statement("
            UPDATE citas c
            INNER JOIN servicio s ON c.servicio = s.name
            SET c.id_servicio = s.id
        ");

        // Ahora sí eliminar columna servicio
        DB::statement("ALTER TABLE citas DROP COLUMN servicio");

        // Crear relación con tabla servicios
        DB::statement("ALTER TABLE citas 
            ADD CONSTRAINT fk_citas_servicio FOREIGN KEY (id_servicio) REFERENCES servicio(id)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Quitar la foreign key hacia servicios
        DB::statement("ALTER TABLE citas DROP FOREIGN KEY fk_citas_servicio");

        // Quitar la foreign key hacia analises
        DB::statement("ALTER TABLE citas DROP FOREIGN KEY fk_citas_analisis");

        // Revertir nombre de columna id_analisis → id_examen_fisico
        DB::statement("ALTER TABLE citas CHANGE id_analisis id_examen_fisico BIGINT UNSIGNED");

        // Restaurar la foreign key original hacia analises
        DB::statement("ALTER TABLE citas 
            ADD CONSTRAINT citas_id_examen_fisico_foreign FOREIGN KEY (id_examen_fisico) REFERENCES analises(id) ON DELETE SET NULL");

        // Eliminar columna id_servicio
        DB::statement("ALTER TABLE citas DROP COLUMN id_servicio");

        // Restaurar columnas eliminadas
        DB::statement("ALTER TABLE citas ADD COLUMN name_paciente VARCHAR(255)");
        DB::statement("ALTER TABLE citas ADD COLUMN name_medico VARCHAR(255)");
        DB::statement("ALTER TABLE citas ADD COLUMN servicio VARCHAR(255)");
    }
};
