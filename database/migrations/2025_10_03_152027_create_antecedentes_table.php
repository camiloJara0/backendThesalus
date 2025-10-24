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
        Schema::create('antecedentes', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_paciente');
            $table->string('tipo', 50);
            $table->text('descripcion');
            $table->timestamps();

            // Clave foránea con ON DELETE CASCADE
            $table->foreign('id_paciente')
                  ->references('id')
                  ->on('pacientes')
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
        Schema::dropIfExists('antecedentes');
    }
};
