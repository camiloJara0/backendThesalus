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
        Schema::create('historial_cambio_sonda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kardex'); // relación con Kardex
            $table->date('fecha_cambio')->nullable();
            $table->string('tipo_sonda')->nullable(); // Ej: cateterismo, gastro, traqueo
            $table->text('observacion')->nullable();

            // Relación con Kardex
            $table->foreign('id_kardex')->references('id')->on('kardex')->onDelete('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_cambio_sonda');
    }
};
