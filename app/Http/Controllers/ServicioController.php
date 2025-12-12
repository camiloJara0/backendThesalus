<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicio = Servicio::get();

        return response()->json([
            'success' => true,
            'data' => $servicio
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
        $servicio = new Servicio();
        $servicio->plantilla = $request->plantilla;
        $servicio->name = $request->name;
        $servicio->save();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Servicio creado exitosamente.',
            'data' => $servicio
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function show(Servicio $servicio)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servicio $servicio)
    {
        // Actualizar los campos
        $servicio = Servicio::where('id', $request->id)->first();
        if($servicio){
            $servicio->plantilla = $request->plantilla ?? null;
            $servicio->name = $request->name;
            $servicio->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Servicio actualizado exitosamente.',
            'data' => $servicio
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servicio $servicio)
    {

    }
}
