<?php

namespace App\Http\Controllers;

use App\Models\Historial_cambio_sonda;
use Illuminate\Http\Request;

class HistorialCambioSondaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $historial = Historial_cambio_sonda::get();

        return response()->json([
            'success' => true,
            'data' => $historial
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

        $nuevo = Historial_cambio_sonda::create([
            'id_kardex' => $request['id_kardex'] ?? null,
            'fecha_cambio' => $request['fecha_cambio'] ?? null,
            'tipo_sonda' => $request['tipo_sonda'] ?? null,
            'observacion' => $request['observacion'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Historial de cambio de sonda creado exitosamente.',
            'data'    => $nuevo
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Historial_cambio_sonda  $Historial_cambio_sonda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Historial_cambio_sonda $Historial_cambio_sonda)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Historial_cambio_sonda $Historial_cambio_sonda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Historial_cambio_sonda $Historial_cambio_sonda)
    {

    }
}
