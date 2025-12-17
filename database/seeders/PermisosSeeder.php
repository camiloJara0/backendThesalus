<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $secciones = [
        //     'Configuracion','Resoluciones','Inventarios','Datos','Usuarios','Negocios','Productos',
        //     'Formas de pago','Impuestos','Cajas','Historias','Consultas','Análisis','Evoluciones',
        //     'Notas','Tratamientos','Medicacion','Pacientes','Profesional','Citas','Crear','Rips','Reportes'
        // ];
        $secciones = [
            'Datos','Usuarios','Historias','Diagnosticos','Pacientes','Profesional','Citas',
            'Notas','Evoluciones','Tratamientos','Medicacion','Terapias','TrabajoSocial','MedicinaGeneral',
            'Configuracion',
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
