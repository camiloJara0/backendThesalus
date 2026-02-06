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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidadMovimiento');
            $table->string('tipoMovimiento', 20); // control de stock
            $table->date('fechaMovimiento');
            // Relaciónes
            $table->unsignedBigInteger('id_medico')->nullable();
            $table->unsignedBigInteger('id_analisis')->nullable();
            $table->unsignedBigInteger('id_insumo');
            $table->timestamps();

            $table->foreign('id_insumo')
                    ->references('id')
                    ->on('insumos')
                    ->onDelete('cascade');

            $table->foreign('id_medico')
                  ->references('id')
                  ->on('profesionals')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('movimientos');
    }
};
