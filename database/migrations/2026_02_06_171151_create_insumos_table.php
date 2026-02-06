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
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('categoria')->nullable();
            $table->string('activo')->nullable(); // principio activo
            $table->boolean('receta')->default(false);
            $table->string('unidad')->nullable(); // Caja, frasco, etc.
            $table->integer('stock')->default(0);
            $table->string('lote')->nullable();
            $table->date('vencimiento')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('estado');
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
        Schema::dropIfExists('insumos');
    }
};
