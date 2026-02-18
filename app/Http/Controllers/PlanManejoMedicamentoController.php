<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Historia_Clinica;
use App\Models\Analisis;
use App\Models\Plan_manejo_medicamento;
use Illuminate\Http\Request;

class PlanManejoMedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicamentos = Plan_manejo_medicamento::get();
        return response()->json([
            'success' => true,
            'data' => $medicamentos
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
        $plan_manejo_medicamento = new Plan_manejo_medicamento();
        $plan_manejo_medicamento->id_analisis = $request->id_analisis;
        $plan_manejo_medicamento->medicamento = $request->medicamento;
        $plan_manejo_medicamento->dosis = $request->dosis;
        $plan_manejo_medicamento->cantidad = $request->cantidad;
        $plan_manejo_medicamento->save();

        // Respuesta
        return response()->json([
            'message' => 'Plan de manejo de medicamento registrado exitosamente.',
            'data' => $plan_manejo_medicamento
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan_manejo_medicamento  $plan_manejo_medicamento
     * @return \Illuminate\Http\Response
     */
    public function show(Plan_manejo_medicamento $plan_manejo_medicamento)
    {
        return $plan_manejo_medicamento;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan_manejo_medicamento  $plan_manejo_medicamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan_manejo_medicamento $plan_manejo_medicamento)
    {
        $plan_manejo_medicamento = Plan_manejo_medicamento::where('id', $request->id)->first();

        if($plan_manejo_medicamento){
            $plan_manejo_medicamento->medicamento = $request->medicamento;
            $plan_manejo_medicamento->dosis = $request->dosis;
            $plan_manejo_medicamento->cantidad = $request->cantidad;
            $plan_manejo_medicamento->save();
        }

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Plan de manejo de medicamento actualizado exitosamente.',
            'data' => $plan_manejo_medicamento
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan_manejo_medicamento  $plan_manejo_medicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan_manejo_medicamento $plan_manejo_medicamento)
    {
        //
    }

    public function imprimirFormulaMedica($id)
    {
        $analisis = Analisis::with('servicio')->find($id);

        $historia = Historia_Clinica::where('id', $analisis->id_historia)->first();

        // Paciente con su información de usuario
        $paciente = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->where('pacientes.id', $historia->id_paciente)
            ->select('pacientes.*', 'informacion_users.*', 'eps.nombre as Eps')
            ->first();

        // Profesional con su información de usuario
        $profesional = DB::table('profesionals')
            ->join('informacion_users', 'profesionals.id_infoUsuario', '=', 'informacion_users.id')
            ->where('profesionals.id', $analisis->id_medico)
            ->select('profesionals.*', 'informacion_users.*')
            ->first();

        $medicamentos = DB::table('plan_manejo_medicamentos')
            ->leftJoin('insumos', 'plan_manejo_medicamentos.medicamento', '=', 'insumos.nombre')
            ->where('plan_manejo_medicamentos.id_analisis', $analisis->id)
            ->select('plan_manejo_medicamentos.*', 'insumos.*')
            ->get();


        $fileName = 'Formula_' . $profesional->name . '_' . $analisis->created_at . '.pdf';

        $pdf = Pdf::loadView('pdf.formulaMedica', compact('paciente','profesional','analisis','medicamentos',));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
