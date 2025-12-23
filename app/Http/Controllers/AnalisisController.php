<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use Illuminate\Http\Request;

class AnalisisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $analisis = Analisis::get();
        return response()->json([
            'success' => true,
            'data' => $analisis
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
        // Crear el registro campo por campo
        $analisis = new Analisis();
        $analisis->id_historia = $request->id_historia;
        $analisis->id_medico = $request->id_medico;
        $analisis->analisis = $request->analisis;
        $analisis->observacion = $request->observacion;
        $analisis->motivo = $request->motivo;
        $analisis->tipoAnalisis = $request->tipoAnalisis;
        $analisis->tratamiento = $request->tratamiento;
        $analisis->save();

        // Respuesta
        return response()->json([
            'message' => 'Análisis registrado exitosamente.',
            'data' => $analisis
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Analisis  $analisis
     * @return \Illuminate\Http\Response
     */
    public function show(Analisis $analisis)
    {
        return $analisis;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Analisis  $analisis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Analisis $analisis)
    {
        $analisis = Analisis::where('id', $request->id)->first();
        if($analisis){
            $analisis->analisis = $request->analisis;
            $analisis->observacion = $request->observacion;
            $analisis->motivo = $request->motivo;
            $analisis->tipoAnalisis = $request->tipoAnalisis;
            $analisis->tratamiento = $request->tratamiento;
            $analisis->save();
        }

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Análisis actualizado exitosamente.',
            'data' => $analisis
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Analisis  $analisis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Analisis $analisis)
    {
        //
    }
}
