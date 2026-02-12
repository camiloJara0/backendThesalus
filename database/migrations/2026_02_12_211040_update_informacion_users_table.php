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
        DB::statement('ALTER TABLE informacion_users MODIFY telefono VARCHAR(20) NULL');
        // Actualizar los registros: convertir '0' en NULL
        DB::statement("UPDATE informacion_users SET telefono = NULL WHERE telefono = '0'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir la columna a NOT NULL
        DB::statement("ALTER TABLE informacion_users MODIFY telefono VARCHAR(20) NOT NULL");
        // Opcional: volver a poner '0' en los NULL
        DB::statement("UPDATE informacion_users SET telefono = '0' WHERE telefono IS NULL");

    }
};
