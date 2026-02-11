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
        // Agregar columna estado con valor por defecto 1
        DB::statement("ALTER TABLE servicio ADD COLUMN estado TINYINT(1) NOT NULL DEFAULT 1");

        // Actualizar registros existentes para que tengan estado = 1
        DB::statement("UPDATE servicio SET estado = 1");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar columna estado si se hace rollback
        DB::statement("ALTER TABLE servicio DROP COLUMN estado");
    }
};
