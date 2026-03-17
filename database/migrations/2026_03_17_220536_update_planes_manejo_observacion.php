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
        DB::statement("ALTER TABLE plan_manejo_medicamentos ADD COLUMN observacion TEXT AFTER cantidad");

        DB::statement("ALTER TABLE plan_manejo_procedimientos ADD COLUMN observacion TEXT AFTER codigo");
        DB::statement("ALTER TABLE plan_manejo_procedimientos ADD COLUMN id_analisis BIGINT UNSIGNED AFTER id");

        DB::statement("ALTER TABLE plan_manejo_equipos ADD COLUMN observacion TEXT AFTER uso");

        DB::statement("ALTER TABLE plan_manejo_insumos ADD COLUMN observacion TEXT AFTER cantidad");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE plan_manejo_medicamentos DROP COLUMN observacion");

        DB::statement("ALTER TABLE plan_manejo_procedimientos DROP COLUMN observacion");
        DB::statement("ALTER TABLE plan_manejo_procedimientos DROP COLUMN id_analisis");

        DB::statement("ALTER TABLE plan_manejo_equipos DROP COLUMN observacion");

        DB::statement("ALTER TABLE plan_manejo_insumos DROP COLUMN observacion");

    }
};
