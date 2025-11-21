<?php

namespace App\Http\Controllers;

use App\Models\Plan_manejo_insumo;
use Illuminate\Http\Request;

class PlanManejoInsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insumos = Plan_manejo_insumo::get();
        return response()->json([
            'success' => true,
            'data' => $insumos
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
        $plan_maenjo_insumo = new Plan_manejo_insumo();
        $plan_maenjo_insumo->id_analisis = $request->id_analisis;
        $plan_maenjo_insumo->cups = $request->cups;
        $plan_maenjo_insumo->nombre = $request->nombre;
        $plan_maenjo_insumo->presentacion = $request->presentacion;
        $plan_maenjo_insumo->cantidad = $request->cantidad;
        $plan_maenjo_insumo->save();

        // Respuesta
        return response()->json([
            'message' => 'Plan de manejo de insumo registrado exitosamente.',
            'data' => $plan_maenjo_insumo
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan_manejo_insumo  $plan_manejo_insumo
     * @return \Illuminate\Http\Response
     */
    public function show(Plan_manejo_insumo $plan_manejo_insumo)
    {
        return $plan_manejo_insumo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan_manejo_insumo  $plan_manejo_insumo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan_manejo_insumo $plan_manejo_insumo)
    {
        $plan_maenjo_insumo->id_analisis = $request->id_analisis;
        $plan_maenjo_insumo->cups = $request->cups;
        $plan_maenjo_insumo->nombre = $request->nombre;
        $plan_maenjo_insumo->presentacion = $request->presentacion;
        $plan_maenjo_insumo->cantidad = $request->cantidad;
        $plan_maenjo_insumo->save();

        // Respuesta
        return response()->json([
            'message' => 'Plan de manejo de insumo actualizado exitosamente.',
            'data' => $plan_maenjo_insumo
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan_manejo_insumo  $plan_manejo_insumo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan_manejo_insumo $plan_manejo_insumo)
    {
        //
    }
}
