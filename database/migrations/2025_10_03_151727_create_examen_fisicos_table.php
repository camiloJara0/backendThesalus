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
        Schema::create('examen_fisicos', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->decimal('peso', 5, 2);
            $table->decimal('altura', 5, 2);
            $table->text('otros');
            $table->json('signosVitales');
            $table->unsignedBigInteger('id_analisis');
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
        Schema::dropIfExists('examen_fisicos');
    }
};
