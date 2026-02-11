<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Plan_manejo_procedimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cita = DB::table('citas')
        ->join('pacientes', 'citas.id_paciente', '=', 'pacientes.id')
        ->join('informacion_users as infoPaciente', 'pacientes.id_infoUsuario', '=', 'infoPaciente.id')
        ->join('profesionals', 'citas.id_medico', '=', 'profesionals.id')
        ->join('informacion_users as infoMedico', 'profesionals.id_infoUsuario', '=', 'infoMedico.id')
        ->join('servicio', 'citas.id_servicio', '=', 'servicio.id')
        ->select(
            'citas.*',
            'infoPaciente.name as name_paciente',
            'infoMedico.name as name_medico',
            'servicio.name as servicio'
        )
        ->get();
;

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
        $cita->id_analisis        = null;
        $cita->id_servicio        = $request->id_servicio;
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
        $cita = Cita::where('id', $request->id)->first();

        if(!$cita){
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada',
            ]);
        }

        $cita->id_servicio = $request->id_servicio;
        $cita->motivo = $request->motivo;
        $cita->fecha = $request->fecha;
        $cita->fechaHasta = $request->fechaHasta;
        $cita->hora = $request->hora;
        $cita->motivo_edicion = $request->motivo_edicion;
        $cita->save();

        // Respuesta 
        return response()->json([
            'success' => true,
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
