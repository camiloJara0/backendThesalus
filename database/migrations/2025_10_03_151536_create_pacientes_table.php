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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_eps')->nullable();
            $table->unsignedBigInteger('id_infoUsuario')->nullable();
            $table->string('genero', 20);
            $table->string('sexo', 20);
            $table->string('regimen', 20);
            $table->string('vulnerabilidad', 100);
            $table->integer('estado')->default(1);
            $table->timestamps();

            // Claves foráneas con ON DELETE SET NULL
            $table->foreign('id_eps')
                  ->references('id')
                  ->on('eps')
                  ->onDelete('set null');

            $table->foreign('id_infoUsuario')
                  ->references('id')
                  ->on('informacion_users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
};
