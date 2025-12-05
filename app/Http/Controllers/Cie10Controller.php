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
        $cie10 = Cie_10::get();

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
        $data = $request->all();
        $codigos = [];

        foreach ($data['Cie10'] ?? [] as $cie10) {
            $nuevo = Cie_10::create([...$cie10]);
            $codigos['Cie10'][] = $nuevo;
        }

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Cie10 creado exitosamente.',
            'data' => $codigos
        ], 200);
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
