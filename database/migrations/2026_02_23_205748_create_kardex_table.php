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
        Schema::create('kardex', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_paciente')->nullable(); // relación con pacientes
            $table->string('responsable')->nullable();
            $table->boolean('kit_cateterismo')->nullable();
            $table->string('rango')->nullable();
            $table->boolean('kit_cambioSonda')->nullable();
            $table->boolean('kit_gastro')->nullable();
            $table->boolean('traqueo')->nullable();
            $table->text('equipos_biomedicos')->nullable();
            $table->boolean('oxigeno')->nullable();
            $table->string('estado')->nullable();
            $table->string('vm')->nullable();
            $table->date('ultimoCambio')->nullable();
            
            // Relación con tabla pacientes (opcional)
            $table->foreign('id_paciente')->references('id')->on('pacientes')->onDelete('cascade');
            
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
        Schema::dropIfExists('kardex');
    }
};
 // Crear una fila en Kardex por cada paciente existente
        // DB::statement("
        //     INSERT INTO kardex (id_paciente, responsable, kit_cateterismo, rango, kit_cambioSonda, kit_gastro, traqueo, equipos_biomedicos, oxigeno, estado, vm, ultimoCambio, created_at, updated_at)
        //     SELECT id, NULL, FALSE, NULL, FALSE, FALSE, FALSE, NULL, FALSE, NULL, NULL, NULL, NOW(), NOW()
        //     FROM pacientes
        // ");

