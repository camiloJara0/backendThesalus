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
        Schema::create('codigo_verificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('correo')->index();
            $table->string('codigo');
            $table->timestamp('expira_en')->nullable();
            $table->boolean('usado')->default(false);
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
        Schema::dropIfExists('codigo_verificaciones');
    }
};
