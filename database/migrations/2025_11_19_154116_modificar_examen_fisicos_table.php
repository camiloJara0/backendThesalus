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
        Schema::table('examen_fisicos', function (Blueprint $table) {
            // Eliminar la columna actual
            $table->dropColumn('otros');
        });

        Schema::table('examen_fisicos', function (Blueprint $table) {
            // Volver a crear la columna como nullable
            $table->text('otros')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('examen_fisicos', function (Blueprint $table) {
            $table->dropColumn('otros');
        });

        Schema::table('examen_fisicos', function (Blueprint $table) {
            $table->text('otros'); // No nullable
        });
    }
};
