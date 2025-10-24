<?php

namespace App\Http\Controllers;

use App\Models\Software;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $software = Software::get();

        return response()->json([
            'success' => true,
            'data' => $software
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

        // Crear el nuevo registro de software
        $software = new Software();
        $software->Tipo = $request->Tipo;
        $software->idSoftware = $request->idSoftware;
        $software->pin = $request->pin;
        $software->testID = $request->testID;
        $software->save();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Software registrado exitosamente.',
            'data' => $software
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function show(Software $software)
    {
        return $software;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Software $software)
    {
        $software->Tipo = $request->Tipo;
        $software->id = $request->id;
        $software->pin = $request->pin;
        $software->testID = $request->testID;
        $software->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Software actualizado exitosamente.',
            'data' => $software
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Software  $software
     * @return \Illuminate\Http\Response
     */
    public function destroy(Software $software)
    {
        //
    }
}
