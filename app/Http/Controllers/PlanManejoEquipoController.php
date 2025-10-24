<?php

namespace App\Http\Controllers;

use App\Models\Plan_manejo_equipo;
use Illuminate\Http\Request;

class PlanManejoEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Plan_manejo_equipo::with(['analisis'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $plan_manejo_equipo = new Plan_manejo_equipo();
        $plan_manejo_equipo->id_analisis = $request->id_analisis;
        $plan_manejo_equipo->cups = $request->cups;
        $plan_manejo_equipo->descripcion = $request->descripcion;
        $plan_manejo_equipo->cantidad = $request->cantidad;
        $plan_manejo_equipo->save();

        // Respuesta
        return response()->json([
            'message' => 'Plan de manejo de equipo registrado exitosamente.',
            'data' => $plan_manejo_equipo
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan_manejo_equipo  $plan_manejo_equipo
     * @return \Illuminate\Http\Response
     */
    public function show(Plan_manejo_equipo $plan_manejo_equipo)
    {
        return $plan_manejo_equipo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan_manejo_equipo  $plan_manejo_equipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan_manejo_equipo $plan_manejo_equipo)
    {
        $plan_manejo_equipo->id_analisis = $request->id_analisis;
        $plan_manejo_equipo->cups = $request->cups;
        $plan_manejo_equipo->descripcion = $request->descripcion;
        $plan_manejo_equipo->cantidad = $request->cantidad;
        $plan_manejo_equipo->save();

        // Respuesta
        return response()->json([
            'message' => 'Plan de manejo de equipo registrado exitosamente.',
            'data' => $plan_manejo_equipo
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan_manejo_equipo  $plan_manejo_equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan_manejo_equipo $plan_manejo_equipo)
    {
        //
    }
}
