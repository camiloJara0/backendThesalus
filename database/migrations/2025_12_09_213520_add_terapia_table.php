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
        Schema::table('terapia', function (Blueprint $table) {
            $table->unsignedBigInteger('id_analisis')->nullable();
            $table->foreign('id_analisis')->references('id')->on('analises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terapia', function (Blueprint $table) {
            // Revertir cambios: eliminar la nueva llave y columna
            $table->dropForeign(['id_analisis']);
            $table->dropColumn('id_analisis');
        });
    }
};
