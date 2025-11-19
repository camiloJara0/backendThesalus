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
        Schema::table('plan_manejo_procedimientos', function (Blueprint $table) {
            $table->integer('dias_asignados')->default(0);

            $table->unsignedBigInteger('id_medico')->nullable();
            $table->foreign('id_medico')->references('id')->on('profesionals');
        });

        Schema::table('plan_manejo_procedimientos', function (Blueprint $table) {
            $table->dropColumn(['fecha']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_manejo_procedimientos', function (Blueprint $table) {
            $table->dropColumn(['fecha']);
        });
    }
};
