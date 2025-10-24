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
        Schema::create('eps', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->string('codigo', 20);
            $table->string('nombre', 100);
            $table->string('direccion', 100);
            $table->string('telefono', 100);
            $table->string('email', 100);
            $table->string('website', 100);
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
        Schema::dropIfExists('eps');
    }
};
