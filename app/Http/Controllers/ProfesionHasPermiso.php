<?php

namespace App\Http\Controllers;

use App\Models\Profesion;
use App\Models\Secciones;
use Illuminate\Http\Request;

class ProfesionHasPermisoController extends Controller
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
        // Crear la nueva profesión
        $profesion = new Profesion();
        $profesion->codigo = $request->codigo;
        $profesion->nombre = $request->nombre;
        $profesion->save();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Profesión creada exitosamente.',
            'data' => $profesion
        ], 201);
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
        // Actualizar los campos
        $profesion = Profesion::where('id', $request->id)->first();
        if($profesion){
            $profesion->codigo = $request->codigo;
            $profesion->nombre = $request->nombre;
            $profesion->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Profesión actualizada exitosamente.',
            'data' => $profesion
        ], 200);

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
