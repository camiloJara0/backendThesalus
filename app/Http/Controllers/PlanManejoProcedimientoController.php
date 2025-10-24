<?php

namespace App\Http\Controllers;

use App\Models\Plan_manejo_procedimiento;
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
        $plan_manejo_procedimiento->cups = $request->cups;
        $plan_manejo_procedimiento->descripcion = $request->descripcion;
        $plan_manejo_procedimiento->cantidad = $request->cantidad;
        $plan_manejo_procedimiento->mes = $request->mes;
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
}
