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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id(); // Equivalente a SERIAL + PRIMARY KEY
            $table->string('nombre', 100);
            $table->string('no_identificacion', 30);
            $table->string('DV', 5);
            $table->text('direccion');
            $table->string('municipio', 100);
            $table->string('pais', 100);
            $table->string('telefono', 20);
            $table->string('lenguaje', 50);
            $table->string('tipoDocumento', 50);
            $table->string('tipoEntorno', 50);
            $table->string('tipoMoneda', 50);
            $table->string('tipoOperacion', 50);
            $table->string('tipoOrganizacion', 50);
            $table->string('tipoRegimen', 50);
            $table->string('tipoResponsabilidad', 50);
            $table->string('impuesto', 50);
            $table->string('registroMercantil', 50);
            $table->text('logo')->nullable();
            $table->text('logoLogin')->nullable();
            $table->text('JPG')->nullable();
            $table->integer('estado')->default(1);
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
        Schema::dropIfExists('empresas');
    }
};
