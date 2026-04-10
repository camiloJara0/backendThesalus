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
        Schema::create('vadecums', function (Blueprint $table) {
            $table->id();

            // Campos del JSON
            $table->string('expediente')->nullable();
            $table->string('producto')->nullable();
            $table->string('titular')->nullable();
            $table->string('registrosanitario')->nullable();
            $table->date('fechaexpedicion')->nullable();
            $table->date('fechavencimiento')->nullable();
            $table->string('estadoregistro')->nullable();
            $table->string('expedientecum')->nullable();
            $table->string('consecutivocum')->nullable();
            $table->string('cantidadcum')->nullable();
            $table->text('descripcioncomercial')->nullable();
            $table->string('estadocum')->nullable();
            $table->date('fechaactivo')->nullable();
            $table->date('fechainactivo')->nullable();
            $table->string('muestramedica')->nullable();
            $table->string('unidad')->nullable();
            $table->string('atc')->nullable();
            $table->string('descripcionatc')->nullable();
            $table->string('viaadministracion')->nullable();
            $table->string('concentracion')->nullable();
            $table->string('principioactivo')->nullable();
            $table->string('unidadmedida')->nullable();
            $table->string('cantidad')->nullable();
            $table->string('unidadreferencia')->nullable();
            $table->string('formafarmaceutica')->nullable();
            $table->string('nombrerol')->nullable();
            $table->string('tiporol')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('IUM')->nullable();
            $table->string('estado')->default('activo');

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
        Schema::dropIfExists('vadecums');
    }
};
