<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Plan_manejo_procedimiento;
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
        $cita = Cita::get();

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
        $plan = null;

        if ($request->procedimiento) {
            // Validar si ya existe el procedimiento para el paciente
            $plan = Plan_manejo_procedimiento::where('id_paciente', $request->id_paciente)
                ->where('codigo', $request->codigo)
                ->first();

            if ($plan) {
                // Si existe, sumar los días_asignados
                $plan->dias_asignados += 1;
                $plan->save();
            } else {
                // Si no existe, crear nuevo
                $plan = Plan_manejo_procedimiento::create([
                    'id_paciente'    => $request->id_paciente,
                    'id_medico'      => $request->id_medico,
                    'procedimiento'  => $request->procedimiento,
                    'codigo'         => $request->codigo,
                    'dias_asignados' => $request->dias_asignados ?? 1,
                ]);
            }
        }

        $cita = new Cita();
        $cita->id_paciente        = $request->id_paciente;
        $cita->id_medico          = $request->id_medico;
        $cita->id_examen_fisico   = null;
        $cita->name_paciente      = $request->name_paciente;
        $cita->name_medico        = $request->name_medico;
        $cita->servicio           = $request->servicio;
        $cita->motivo             = $request->motivo;
        $cita->fecha              = $request->fecha;
        $cita->fechaHasta         = $request->fechaHasta;
        $cita->hora               = $request->hora ?? '00:00:00';
        $cita->estado             = 'Inactiva';
        $cita->motivo_cancelacion = null;
        $cita->id_procedimiento   = $plan ? $plan->id : $request->id_procedimiento;
        $cita->save();

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Cita registrada exitosamente.',
            'data'    => $cita
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
        $cita->fechaHasta = $request->fechaHasta;
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
