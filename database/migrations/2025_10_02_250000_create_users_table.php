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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->unsignedBigInteger('id_infoUsuario');
            $table->string('correo', 100);
            $table->string('contraseña', 100)->nullable();
            $table->string('rol', 50);
            $table->integer('estado')->default(1);
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_empresa')
                  ->references('id')
                  ->on('empresas')
                  ->onDelete('cascade');

            // Clave foránea
            $table->foreign('id_infoUsuario')
                  ->references('id')
                  ->on('informacion_users')
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
        Schema::dropIfExists('users');
    }
};
