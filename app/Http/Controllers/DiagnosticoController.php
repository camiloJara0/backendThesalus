<?php

namespace App\Http\Controllers;

use App\Models\Diagnostico;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diagnosticos = Diagnostico::get();
        return response()->json([
            'success' => true,
            'data' => $diagnosticos
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
        // Crear el nuevo diagnóstico
        $diagnostico = new Diagnostico();
        $diagnostico->id_paciente = $request->id_paciente;
        $diagnostico->id_profesional = $request->id_profesional;
        $diagnostico->codigoCIE_10 = $request->codigoCIE_10;
        $diagnostico->CIE_10 = $request->CIE_10;
        $diagnostico->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Diagnóstico creado exitosamente.',
            'data' => $diagnostico
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diagnostico  $diagnostico
     * @return \Illuminate\Http\Response
     */
    public function show(Diagnostico $diagnostico)
    {
        return $diagnostico;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diagnostico  $diagnostico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diagnostico $diagnostico)
    {
        $diagnostico->id_paciente = $request->id_paciente;
        $diagnostico->id_profesional = $request->id_profesional;
        $diagnostico->codigoCIE_10 = $request->codigoCIE_10;
        $diagnostico->CIE_10 = $request->CIE_10;
        $diagnostico->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Diagnóstico actualizado exitosamente.',
            'data' => $diagnostico
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diagnostico  $diagnostico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diagnostico $diagnostico)
    {
        //
    }
}
