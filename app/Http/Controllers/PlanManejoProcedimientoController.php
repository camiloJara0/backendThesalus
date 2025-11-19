<?php

namespace App\Http\Controllers;

use App\Models\Plan_manejo_procedimiento;
use App\Models\Cita;
use App\Models\Historia_Clinica;
use App\Models\Analisis;
use Illuminate\Http\Request;

class PlanManejoProcedimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Plan_manejo_procedimiento::with(['analisis'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $plan_manejo_procedimiento = new Plan_manejo_procedimiento();
        $plan_manejo_procedimiento->id_analisis = $request->id_analisis;
        $plan_manejo_procedimiento->id_medico = $request->id_medico;
        $plan_manejo_procedimiento->codigo = $request->codigo;
        $plan_manejo_procedimiento->procedimiento = $request->procedimiento;
        $plan_manejo_procedimiento->dias_asignados = $request->dias_asignados;
        $plan_manejo_procedimiento->save();

        // Respuesta
        return response()->json([
            'message' => 'Plan de manejo de procedimiento registrado exitosamente.',
            'data' => $plan_manejo_procedimiento
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan_manejo_procedimiento  $plan_manejo_procedimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Plan_manejo_procedimiento $plan_manejo_procedimiento)
    {
        return $plan_manejo_procedimiento;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan_manejo_procedimiento  $plan_manejo_procedimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan_manejo_procedimiento $plan_manejo_procedimiento)
    {
        $plan_manejo_procedimiento->id_analisis = $request->id_analisis;
        $plan_manejo_procedimiento->cups = $request->cups;
        $plan_manejo_procedimiento->descripcion = $request->dscripcion;
        $plan_manejo_procedimiento->cantidad = $request->cantidad;
        $plan_manejo_procedimiento->mes = $request->mes;
        $plan_manejo_procedimiento->save();

        // Respuesta
        return response()->json([
            'message' => 'Plan de manejo de procedimiento actualizado exitosamente.',
            'data' => $plan_manejo_procedimiento
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan_manejo_procedimiento  $plan_manejo_procedimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan_manejo_procedimiento $plan_manejo_procedimiento)
    {
        //
    }

    public function diasAsignadosRestantes(Request $request)
    {
        $id_paciente = $request->id_paciente;

        // Obtener todos los planes de manejo del paciente

        // 1. Obtener la historia clínica del paciente
        $historia = Historia_Clinica::where('id_paciente', $id_paciente)->first();

        if (!$historia) {
            return response()->json(['success' => false, 'message' => 'No se encontró historia clínica para el paciente.'], 200);
        }

        // 2. Obtener los análisis asociados a la historia
         $analisis = Analisis::where('id_historia', $historia->id)->get();

        if ($analisis->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No se encontraron análisis para la historia.'], 200);
        }

        // 3. Obtener los planes de manejo por cada análisis, filtrando días_asignados >= 1
        $planes = Plan_manejo_procedimiento::whereIn('id_analisis', $analisis->pluck('id'))
            ->where('dias_asignados', '>=', 1)
            ->get();


        $resultado = [];

        foreach ($planes as $plan) {
            // Contar las citas realizadas donde coincidan paciente y plan de procedimiento
            $citasRealizadas = Cita::where('id_paciente', $id_paciente)
                ->where('id_procedimiento', $plan->id)
                ->count();

            // Calcular días restantes
            $diasRestantes = max(0, $plan->dias_asignados - $citasRealizadas);

            $resultado[] = [
                'tratamiento' => $plan->procedimiento,
                'id' => $plan->id,
                'dias_asignados' => $plan->dias_asignados,
                'citas_realizadas' => $citasRealizadas,
                'dias_restantes' => $diasRestantes
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Días asignados restantes por tratamiento',
            'data' => $resultado
        ]);
    }
}
