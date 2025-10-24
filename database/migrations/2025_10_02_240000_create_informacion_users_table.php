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
        Schema::create('informacion_users', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->string('name', 100);
            $table->string('No_document', 20)->unique();
            $table->string('type_doc', 20)->nulleable();
            $table->string('celular', 20)->nulleable();
            $table->string('telefono', 20)->nulleable();
            $table->date('nacimiento')->nulleable();
            $table->text('direccion')->nulleable();
            $table->string('municipio', 100)->nulleable();
            $table->string('departamento', 100)->nulleable();
            $table->string('barrio', 100)->nulleable();
            $table->string('zona', 50)->nulleable();
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
        Schema::dropIfExists('informacion_users');
    }
};
