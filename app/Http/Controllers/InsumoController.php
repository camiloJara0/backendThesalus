<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insumos = Insumo::where('id', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $insumos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'activo' => 'nullable|string|max:100',
            'receta' => 'boolean',
            'unidad' => 'nullable|string|max:50',
            'stock' => 'integer|min:0',
            'lote' => 'nullable|string|max:50',
            'vencimiento' => 'nullable|date',
            'ubicacion' => 'nullable|string|max:100',
        ]);

        $validated['estado'] = 1;
        $insumo = Insumo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Insumo creado correctamente',
            'data' => $insumo
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function show(Insumo $insumo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function edit(Insumo $insumo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Insumo $insumo)
    {
        $insumo = Insumo::findOrFail($request->id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'activo' => 'nullable|string|max:100',
            'receta' => 'boolean',
            'unidad' => 'nullable|string|max:50',
            'stock' => 'integer|min:0',
            'lote' => 'nullable|string|max:50',
            'vencimiento' => 'nullable|date',
            'ubicacion' => 'nullable|string|max:100',
        ]);

        $insumo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Insumo actualizado correctamente',
            'data' => $insumo
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Insumo  $insumo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Insumo $insumo)
    {
        $insumo = Insumo::findOrFail($request->id);

        $insumo->estado = 0;
        $insumo-> save();

        return response()->json([
            'success' => true,
            'message' => 'Insumo actualizado correctamente',
            'data' => $insumo
        ], 200);
    }
}
