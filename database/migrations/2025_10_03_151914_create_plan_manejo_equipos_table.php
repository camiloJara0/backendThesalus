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
        Schema::create('plan_manejo_equipos', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_analisis');
            $table->text('descripcion');
            $table->text('uso');
            $table->timestamps();

            // Clave foránea con ON DELETE CASCADE
            $table->foreign('id_analisis')
                  ->references('id')
                  ->on('analises')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_manejo_equipos');
    }
};
