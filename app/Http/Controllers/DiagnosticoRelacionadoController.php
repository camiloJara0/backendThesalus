<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnostico_relacionado;

class DiagnosticoRelacionadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diagnostico_relacionados = Diagnostico_relacionado::get();
        return response()->json([
            'success' => true,
            'data' => $diagnostico_relacionados
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
        $diagnostico_relacionado = new Diagnostico_relacionado();
        $diagnostico_relacionado->id_paciente = $request->id_paciente;
        $diagnostico_relacionado->id_profesional = $request->id_profesional;
        $diagnostico_relacionado->codigoCIE_10 = $request->codigoCIE_10;
        $diagnostico_relacionado->CIE_10 = $request->CIE_10;
        $diagnostico_relacionado->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Diagnóstico creado exitosamente.',
            'data' => $diagnostico_relacionado
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diagnostico_relacionado  $diagnostico_relacionado
     * @return \Illuminate\Http\Response
     */
    public function show(Diagnostico_relacionado $diagnostico_relacionado)
    {
        return $diagnostico_relacionado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diagnostico_relacionado  $diagnostico_relacionado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diagnostico_relacionado $diagnostico_relacionado)
    {
        $diagnostico_relacionado->id_paciente = $request->id_paciente;
        $diagnostico_relacionado->id_profesional = $request->id_profesional;
        $diagnostico_relacionado->codigoCIE_10 = $request->codigoCIE_10;
        $diagnostico_relacionado->CIE_10 = $request->CIE_10;
        $diagnostico_relacionado->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Diagnóstico actualizado exitosamente.',
            'data' => $diagnostico_relacionado
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diagnostico_relacionado  $diagnostico_relacionado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diagnostico_relacionado $diagnostico_relacionado)
    {
        //
    }

}
