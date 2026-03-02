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
        Schema::create('solicitud_permisos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_profesional');
            $table->unsignedBigInteger('id_seccion');
            $table->string('token_aprobacion');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado']);
            
            $table->foreign('id_profesional')
                ->references('id')
                ->on('profesionals')
                ->onDelete('cascade');

            $table->foreign('id_seccion')
                ->references('id')
                ->on('secciones')
                ->onDelete('cascade');

            $table->timestamps();
        });

        DB::statement("ALTER TABLE profesional_has_permisos ADD COLUMN usado TINYINT(1) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE profesional_has_permisos ADD COLUMN codigo VARCHAR(100) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitud_permisos');
        DB::statement("ALTER TABLE profesional_has_permisos DROP COLUMN usado");
        DB::statement("ALTER TABLE profesional_has_permisos DROP COLUMN codigo");
    }
};
