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
        Schema::create('profesions_has_permisos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_profesion');
            $table->unsignedBigInteger('id_seccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profesions_has_permisos');
    }
};
