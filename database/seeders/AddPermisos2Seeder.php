<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPermisos2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
        public function run()
        {
            // Permisos que quieres agregar
            $permisos = [
                ['nombre' => 'ListaPacientes'],
            ];

            // Insertar los nuevos permisos
            DB::table('secciones')->insert($permisos);

            // Permisos que quieres eliminar
            $permisosEliminar = [
                "Diagnosticos_get",
                "Diagnosticos_post",
                "Diagnosticos_put",
                "Diagnosticos_delete",
            ];

            // Eliminar los registros cuyo nombre esté en la lista
            DB::table('secciones')
                ->whereIn('nombre', $permisosEliminar)
                ->delete();
        }

}
