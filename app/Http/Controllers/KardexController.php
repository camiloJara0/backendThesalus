<?php

namespace App\Http\Controllers;

use App\Models\Kardex;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kardex = Kardex::get();

        return response()->json([
            'success' => true,
            'data' => $kardex
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


            // Busca por paciente y actualiza o crea
            $nuevo = Kardex::updateOrCreate(
                ['id_paciente' => $request['id_paciente']], // condición de búsqueda
                [
                    'responsable'       => $request['responsable'] ?? null,
                    'kit_cateterismo'   => $request['kit_cateterismo'] ? true : false,
                    'rango'             => $request['rango'] ?? null,
                    'kit_cambioSonda'   => $request['kit_cambioSonda'] ? true : false,
                    'kit_gastro'        => $request['kit_gastro'] ? true : false,
                    'traqueo'           => $request['traqueo'] ? true : false,
                    'equipos_biomedicos'=> $request['equipos_biomedicos'] ?? null,
                    'oxigeno'           => $request['oxigeno'] ? true : false,
                    'estado'            => $request['estado'] ?? null,
                    'vm'                => $request['vm'] ?? null,
                    'ultimoCambio'      => $request['ultimoCambio'] ?? null,
                ]
            );


        return response()->json([
            'success' => true,
            'message' => 'Kardex creado o actualizado exitosamente.',
            'data'    => $codigos
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kardex  $Kardex
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kardex $Kardex)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kardex  $Kardex
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kardex $Kardex)
    {

    }
}
