<?php

namespace App\Http\Controllers;

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
        return Plan_manejo_medicamento::with(['analisis'])->get();
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
        $plan_manejo_medicamento = new PlanManejoMedicamento();
        $plan_manejo_medicamento->id_analisis = $request->id_analisis;
        $plan_manejo_medicamento->cups = $request->cups;
        $plan_manejo_medicamento->medicamento = $request->medicamento;
        $plan_manejo_medicamento->presentacion = $request->presentacion;
        $plan_manejo_medicamento->concentracion = $request->concentracion;
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
        $plan_manejo_medicamento->id_analisis = $request->id_analisis;
        $plan_manejo_medicamento->cups = $request->cups;
        $plan_manejo_medicamento->medicamento = $request->medicamento;
        $plan_manejo_medicamento->presentacion = $request->presentacion;
        $plan_manejo_medicamento->concentracion = $request->concentracion;
        $plan_manejo_medicamento->dosis = $request->dosis;
        $plan_manejo_medicamento->cantidad = $request->cantidad;
        $plan_manejo_medicamento->save();

        // Respuesta
        return response()->json([
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
}
