<?php

namespace App\Http\Controllers;

use App\Models\Profesion;
use Illuminate\Support\Facades\DB;
use App\Models\Profesionals;
use App\Models\Profesional_has_permisos;
use App\Models\Secciones;
use Illuminate\Http\Request;

class ProfesionalHasPermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permiso = Profesion::where('estado', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $permiso
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Crear nuevo permiso

            if (!empty($request->permisos) && is_array($request->permisos)) {
                // 1️⃣ Obtener profesional
                $profesional = DB::table('profesionals')
                    ->where('id', $request->id_profesional)
                    ->first();

                if (!$profesional) {
                    throw new \Exception("Profesional no encontrado");
                }

                // 2️⃣ Permisos que ya tiene por profesión
                $permisosProfesion = DB::table('profesions_has_permisos')
                    ->where('id_profesion', $profesional->id_profesion)
                    ->pluck('id_seccion')
                    ->toArray();

                // 3️⃣ Obtener IDs de las secciones solicitadas
                $seccionesSolicitadas = DB::table('secciones')
                    ->whereIn('nombre', $request->permisos)
                    ->pluck('id')
                    ->toArray();

                // 4️⃣ Filtrar solo los que NO tiene por profesión
                $permisosAInsertar = array_diff($seccionesSolicitadas, $permisosProfesion);

                // 5️⃣ Insertar solo los válidos
                foreach ($permisosAInsertar as $idSeccion) {
                    DB::table('profesional_has_permisos')->insert([
                        'id_profesional' => $request->id_profesional,
                        'id_seccion'     => $idSeccion,
                        'fecha_inicio'   => now(),
                        'fecha_fin'      => $request->fecha_fin ?? null,
                    ]);
                }
            };

            DB::commit();

            // 3️⃣ Retornar respuesta
            return response()->json([
                'success' => true,
                'message' => 'Permisos para profesional creadas exitosamente.',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la permisos al profesional.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function show(Profesion $profesion)
    {
        return $profesion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profesion $profesion)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Crear nuevo permiso
            $profesional = Profesional::where('id', $request->id_profesional)->first();

            // 2️⃣ Asociar permisos si vienen en el request
            // 2️⃣ Obtener IDs de permisos desde nombres
            $permisosIds = [];
            if (!empty($request->permisos) && is_array($request->permisos)) {
                $permisosIds = Secciones::whereIn('nombre', $request->permisos)->pluck('id')->toArray();
            }

            // 3️⃣ Sincronizar permisos (agrega nuevos y elimina los que no están)
            $profesional->permisos()->sync($permisosIds);

            DB::commit();

            // 3️⃣ Retornar respuesta
            return response()->json([
                'success' => true,
                'message' => 'Permisos para profesional creadas exitosamente.',
                'data' => $profesion
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la permisos al profesional.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profesion $profesion)
    {
        $profesion->estado = 0;
        $profesion->save();
        response()->json([
            'message' => 'Profesión desactivada exitosamente.'
        ]);

    }
}
