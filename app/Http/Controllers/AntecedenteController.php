<?php

namespace App\Http\Controllers;

use App\Models\Antecedente;
use Illuminate\Http\Request;

class AntecedenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $antecedente = Antecedente::with(['Paciente'])->get();
        return response()->json([
            'success' => true,
            'data' => $antecedente
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
        // Crear el nuevo antecedente
        $antecedente = new Antecedente();
        $antecedente->id_paciente = $request->id_paciente;
        $antecedente->tipo = $request->tipo;
        $antecedente->valor = $request->valor;
        $antecedente->save();

        // Retornar respuesta (puedes personalizar según tu flujo)
        return response()->json([
            'message' => 'Antecedente creado exitosamente.',
            'data' => $antecedente
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Antecedente  $antecedente
     * @return \Illuminate\Http\Response
     */
    public function show(Antecedente $antecedente)
    {
        return $antecedente;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Antecedente  $antecedente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Antecedente $antecedente)
    {
        $antecedente->id_paciente = $request->id_paciente;
        $antecedente->tipo = $request->tipo;
        $antecedente->valor = $request->valor;
        $antecedente->save();

        // Retornar respuesta (puedes personalizar según tu flujo)
        return response()->json([
            'message' => 'Antecedente actualizado exitosamente.',
            'data' => $antecedente
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Antecedente  $antecedente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Antecedente $antecedente)
    {
        //
    }
}
