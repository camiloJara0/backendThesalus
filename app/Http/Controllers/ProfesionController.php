<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Profesion;
use App\Models\Secciones;
use Illuminate\Http\Request;

class ProfesionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profesiones = Profesion::where('estado', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $profesiones
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
            // 1️⃣ Crear la nueva profesión
            $profesion = new Profesion();
            $profesion->codigo = $request->codigo ?? null;
            $profesion->nombre = $request->nombre;
            $profesion->save();

            // 2️⃣ Asociar permisos si vienen en el request
            if (!empty($request->permisos) && is_array($request->permisos)) {
                foreach ($request->permisos as $nombrePermiso) {
                    $permiso = Secciones::where('nombre', $nombrePermiso)->first();

                    if ($permiso) {
                        DB::table('profesions_has_permisos')->insert([
                            'id_profesion' => $profesion->id,
                            'id_seccion' => $permiso->id
                        ]);
                    }
                }
            }

            DB::commit();

            // 3️⃣ Retornar respuesta
            return response()->json([
                'success' => true,
                'message' => 'Profesión creada exitosamente.',
                'data' => $profesion
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la profesión.',
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
    public function show($id)
    {
        $profesion = Profesion::findOrFail($id);
        $permisos = [];

        $permisos = DB::table('profesions_has_permisos')
            ->join('secciones', 'profesions_has_permisos.id_seccion', '=', 'secciones.id')
            ->where('profesions_has_permisos.id_profesion', $profesion->id)
            ->pluck('secciones.nombre');

        return response()->json([
            'success' => true,
            'data' => $permisos
        ]);
    }

    public function showPermisos(Request $request, Profesion $profesion)
    {
        $permisos = [];

        $permisos = DB::table('profesions_has_permisos')
            ->join('secciones', 'profesions_has_permisos.id_seccion', '=', 'secciones.id')
            ->where('profesions_has_permisos.id_profesion', $profesion->id)
            ->pluck('secciones.nombre');

        return response()->json([
            'success' => true,
            'data' => $permisos
        ]);
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
        // Actualizar los campos
        $profesion = Profesion::where('id', $request->id)->first();
        if($profesion){
            $profesion->codigo = $request->codigo ?? null;
            $profesion->nombre = $request->nombre;
            $profesion->save();
        }

        // 2️⃣ Obtener IDs de permisos desde nombres
        $permisosIds = [];
        if (!empty($request->permisos) && is_array($request->permisos)) {
            $permisosIds = Secciones::whereIn('nombre', $request->permisos)->pluck('id')->toArray();
        }

        // 3️⃣ Sincronizar permisos (agrega nuevos y elimina los que no están)
        $profesion->permisos()->sync($permisosIds);


            DB::commit();

            // 3️⃣ Retornar respuesta
            return response()->json([
                'success' => true,
                'message' => 'Profesión actualizada exitosamente.',
                'data' => $profesion
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la profesión.',
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
