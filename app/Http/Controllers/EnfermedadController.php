<?php

namespace App\Http\Controllers;

use App\Models\Enfermedad;
use Illuminate\Http\Request;

class EnfermedadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enfermedades = Enfermedad::get();
        return response()->json([
            'success' => true,
            'data' => $enfermedades
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
        // Crear el nuevo registro de enfermedad
        $enfermedad = new Enfermedad();
        $enfermedad->id_paciente = $request->id_paciente;
        $enfermedad->valor = $request->valor;
        $enfermedad->fecha_diagnostico = $request->fecha_diagnostico;
        $enfermedad->fecha_rehabilitacion = $request->fecha_rehabilitacion;
        $enfermedad->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Enfermedad registrada exitosamente.',
            'data' => $enfermedad
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enfermedad  $enfermedad
     * @return \Illuminate\Http\Response
     */
    public function show(Enfermedad $enfermedad)
    {
        return $enfermedad;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Enfermedad  $enfermedad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Enfermedad $enfermedad)
    {
        $enfermedad->id_paciente = $request->id_paciente;
        $enfermedad->valor = $request->valor;
        $enfermedad->fecha_diagnostico = $request->fecha_diagnostico;
        $enfermedad->fecha_rehabilitacion = $request->fecha_rehabilitacion;
        $enfermedad->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Enfermedad actualizada exitosamente.',
            'data' => $enfermedad
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enfermedad  $enfermedad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enfermedad $enfermedad)
    {
        //
    }
}
