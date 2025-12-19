<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $secciones = [
            'Evoluciones','Tratamientos','Medicacion','Terapias','TrabajoSocial','MedicinaGeneral',
        ];

        $acciones = ['_get', '_post', '_put', '_delete', '_view'];

        $permisos = [];

        foreach ($secciones as $seccion) {
            $clave = str_replace(' ', '_', $seccion);
            foreach ($acciones as $accion) {
                $permisos[] = [
                    'nombre' => $clave . $accion,
                ];
            }
        }

        DB::table('secciones')->insert($permisos);
    }
}
