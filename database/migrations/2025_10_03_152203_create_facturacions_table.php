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
        Schema::create('facturacions', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_empresa');
            $table->string('claveTecnica', 50);
            $table->text('descripcion');
            $table->date('fechaInicial');
            $table->date('fechaHasta');
            $table->date('fechaResolucion');
            $table->string('no_resolucion', 20);
            $table->integer('numeroInicial');
            $table->integer('numeroHasta');
            $table->string('prefijo', 20);
            $table->string('tipoDocumento', 100);
            $table->timestamps();

            // Clave foránea con ON DELETE CASCADE
            $table->foreign('id_empresa')
                  ->references('id')
                  ->on('empresas')
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
        Schema::dropIfExists('facturacions');
    }
};
