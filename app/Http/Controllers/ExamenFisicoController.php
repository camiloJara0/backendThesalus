<?php

namespace App\Http\Controllers;

use App\Models\Examen_fisico;
use Illuminate\Http\Request;

class ExamenFisicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $examenes = Examen_fisico::get();
        return response()->json([
            'success' => true,
            'data' => $examenes
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
        $examen_fisico = new Examen_fisico();
        $examen_fisico->peso = $request->peso;
        $examen_fisico->altura = $request->altura;
        $examen_fisico->otros = $request->otros ?? 'n/a';
        $examen_fisico->signosVitales = json_encode($request->signosVitales); // Convertir array a JSON
        $examen_fisico->id_analisis = $request->id_analisis;
        $examen_fisico->save();

        // Respuesta
        return response()->json([
            'message' => 'Examen físico registrado exitosamente.',
            'data' => $examen_fisico
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Examen_fisico  $examen_fisico
     * @return \Illuminate\Http\Response
     */
    public function show(Examen_fisico $examen_fisico)
    {
        return $examen_fisico;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Examen_fisico  $examen_fisico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Examen_fisico $examen_fisico)
    {
        $examen_fisico->peso = $request->peso;
        $examen_fisico->altura = $request->altura;
        $examen_fisico->otros = $request->otros;
        $examen_fisico->signosVitales = json_encode($request->signosVitales); // Convertir array a JSON
        $examen_fisico->id_analisis = $request->id_analisis;
        $examen_fisico->save();

        // Respuesta
        return response()->json([
            'message' => 'Examen físico registrado exitosamente.',
            'data' => $examen_fisico
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Examen_fisico  $examen_fisico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Examen_fisico $examen_fisico)
    {
        //
    }
}
