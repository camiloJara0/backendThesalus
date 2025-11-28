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
                "name" => "Nota de enfermeria",
                "plantilla" => "Nota",
            ],
            [
                "id" => 2,
                "name" => "Evolucion nutricional",
                "plantilla" => "Evolucion",
            ],
            [
                "id" => 3,
                "name" => "Trabajo Social",
                "plantilla" => "Trabajo Social",
            ],
            [
                "id" => 4,
                "name" => "Medicina",
                "plantilla" => "Medicina",
            ],
            [
                "id" => 5,
                "name" => "Atencion Terapeutica",
                "plantilla" => "Terapia",
            ],
        ];

        DB::table('servicio')->insert($servicio);
    }
}