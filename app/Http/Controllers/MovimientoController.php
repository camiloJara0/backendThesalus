<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Insumo;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movimientos = Movimiento::get();

        return response()->json([
            'success' => true,
            'data' => $movimientos
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
            'cantidadMovimiento' => 'required|integer|min:1',
            'fechaMovimiento' => 'required|date',
            'tipoMovimiento' => 'required|string',
            'id_medico' => 'nullable|integer',
            'id_insumo' => 'required|exists:insumos,id',
        ]);

        $movimiento = Movimiento::create($validated);

        // Actualizar stock del insumo
        $insumo = Insumo::findOrFail($validated['id_insumo']);
        if ($validated['tipoMovimiento'] === 'Ingreso') {
            $insumo->stock += $validated['cantidadMovimiento'];
        } else {
            $insumo->stock -= $validated['cantidadMovimiento'];
        }
        $insumo->save();

        return response()->json([
            'success' => true,
            'message' => 'Movimiento registrado correctamente',
            'data' => $movimiento
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movimiento  $movimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Movimiento $movimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movimiento  $movimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Movimiento $movimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movimiento  $movimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movimiento $movimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movimiento  $movimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movimiento $movimiento)
    {
        //
    }
}
