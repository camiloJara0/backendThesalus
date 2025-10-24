<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cita = Cita::with(['paciente','profesional'])->get();

        return response()->json([
            'success' => true,
            'data' => $cita
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
        $cita = new Cita();
        $cita->id_paciente = $request->id_paciente;
        $cita->id_medico = $request->id_medico;
        $cita->id_examen_fisico = null;
        $cita->name_paciente = $request->name_paciente;
        $cita->name_medico = $request->name_medico;
        $cita->servicio = $request->servicio;
        $cita->motivo = $request->motivo;
        $cita->fecha = $request->fecha;
        $cita->hora = $request->hora;
        $cita->estado = 'Inactiva';
        $cita->motivo_cancelacion = null;
        $cita->save();

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Cita registrada exitosamente.',
            'data' => $cita
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function show(Cita $cita)
    {
        return $cita;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cita $cita)
    {
        $cita->id_paciente = $request->id_paciente;
        $cita->id_medico = $request->id_medico;
        $cita->id_examen_fisico = $request->id_examen_fisico;
        $cita->name_paciente = $request->name_paciente;
        $cita->name_medico = $request->name_medico;
        $cita->servicio = $request->servicio;
        $cita->motivo = $request->motivo;
        $cita->fecha = $request->fecha;
        $cita->hora = $request->hora;
        $cita->estado = $request->estado;
        $cita->motivo_cancelacion = $request->motivo_cancelacion;
        $cita->save();

        // Respuesta
        return response()->json([
            'message' => 'Cita actualizada exitosamente.',
            'data' => $cita
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cita  $cita
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Cita $cita)
    {
        $cita = Cita::where('id', $request->id)->first();
        if($cita){
            $cita->id_paciente = $request->id_paciente;
            $cita->id_medico = $request->id_medico;
            $cita->id_examen_fisico = null;
            $cita->name_paciente = $request->name_paciente;
            $cita->name_medico = $request->name_medico;
            $cita->servicio = $request->servicio;
            $cita->motivo = $request->motivo;
            $cita->fecha = $request->fecha;
            $cita->hora = $request->hora;
            $cita->estado = $request->estado;
            $cita->motivo_cancelacion = $request->motivo_cancelacion;
            $cita->save();
        }

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Cita cancelada exitosamente.',
            'data' => $cita
        ], 200);
    }
}
