<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servicio = [
            [
                "id" => 1,
                "name" => "NOTA DE ENFERMERIA",
                "plantilla" => "Nota",
            ],
            [
                "id" => 2,
                "name" => "EVOLUCION NUTRICIONAL",
                "plantilla" => "Evolucion",
            ],
            [
                "id" => 3,
                "name" => "TRABAJO SOCIAL",
                "plantilla" => "Trabajo Social",
            ],
            [
                "id" => 4,
                "name" => "MEDICINA GENERAL",
                "plantilla" => "Medicina",
            ],
            [
                "id" => 5,
                "name" => "ATENCION TERAPEUTICA",
                "plantilla" => "Terapia",
            ],
        ];

        DB::table('servicio')->insert($servicio);
    }
}