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
        Schema::table('descripcion_nota', function (Blueprint $table) {
            // Eliminar la columna actual
            $table->dropColumn('descripcion');
        });

        Schema::table('descripcion_nota', function (Blueprint $table) {
            // Volver a crear la columna como text
            $table->text('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('descripcion_nota', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });

        Schema::table('descripcion_nota', function (Blueprint $table) {
            $table->string('descripcion');
        });
    }
};
