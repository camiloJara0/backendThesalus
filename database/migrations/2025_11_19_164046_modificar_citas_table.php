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
        Schema::table('citas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_procedimiento')->nullable();
            $table->foreign('id_procedimiento')->references('id')->on('plan_manejo_procedimientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_procedimiento')->nullable();
            $table->foreign('id_procedimiento')->references('id')->on('plan_manejo_procedimientos');
        });
    }
};
