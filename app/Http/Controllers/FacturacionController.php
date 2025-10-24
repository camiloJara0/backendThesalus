<?php

namespace App\Http\Controllers;

use App\Models\Facturacion;
use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facturacion = Facturacion::with(['empresa'])->get();

        return response()->json([
            'success' => true,
            'data' => $facturacion
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
        // Crear el nuevo registro de facturación
        $facturacion = new Facturacion();
        $facturacion->id_empresa = $request->id_empresa;
        $facturacion->claveTecnica = $request->claveTecnica;
        $facturacion->descripcion = $request->descripcion;
        $facturacion->fechaInicial = $request->fechaInicial;
        $facturacion->fechaHasta = $request->fechaHasta;
        $facturacion->fechaResolucion = $request->fechaResolucion;
        $facturacion->no_resolucion = $request->no_resolucion;
        $facturacion->numeroInicial = $request->numeroInicial;
        $facturacion->numeroHasta = $request->numeroHasta;
        $facturacion->prefijo = $request->prefijo;
        $facturacion->tipoDocumento = $request->tipoDocumento;
        $facturacion->save();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Registro de facturación creado exitosamente.',
            'data' => $facturacion
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function show(Facturacion $facturacion)
    {
        return $facturacion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facturacion $facturacion)
    {
        $facturacion->id_empresa = $request->id_empresa;
        $facturacion->claveTecnica = $request->claveTecnica;
        $facturacion->descripcion = $request->descripcion;
        $facturacion->fechaInicial = $request->fechaInicial;
        $facturacion->fechaHasta = $request->fechaHasta;
        $facturacion->fechaResolucion = $request->fechaResolucion;
        $facturacion->no_resolucion = $request->no_resolucion;
        $facturacion->numeroInicial = $request->numeroInicial;
        $facturacion->numeroHasta = $request->numeroHasta;
        $facturacion->prefijo = $request->prefijo;
        $facturacion->tipoDocumento = $request->tipoDocumento;
        $facturacion->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Registro de facturación actualizado exitosamente.',
            'data' => $facturacion
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facturacion $facturacion)
    {
        //
    }
}
