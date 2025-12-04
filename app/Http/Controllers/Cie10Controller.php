<?php

namespace App\Http\Controllers;

use App\Models\Cie_10;
use Illuminate\Http\Request;

class Cie10Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cie10 = Cie10::get();

        return response()->json([
            'success' => true,
            'data' => $cie10
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
        // Crear la nueva profesión
        $cie10 = new Cie10();
        $cie10->nombre = $request->nombre;
        $cie10->codigo = $request->codigo;
        $cie10->save();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Cie10 creado exitosamente.',
            'data' => $cie10
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cie10  $cie10
     * @return \Illuminate\Http\Response
     */
    public function show(Cie10 $cie10)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cie10  $cie10
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cie10 $cie10)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cie10  $cie10
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cie10 $cie10)
    {

    }
}
