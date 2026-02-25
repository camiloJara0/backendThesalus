<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPermisosKardex extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $secciones = [
            'Kardex'
        ];

        $acciones = ['_get', '_put', '_view'];

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
