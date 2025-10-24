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
        Schema::create('plan_manejo_insumos', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('id_analisis');
            $table->string('nombre', 100);
            $table->integer('cantidad');
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
        Schema::dropIfExists('plan_manejo_insumos');
    }
};
