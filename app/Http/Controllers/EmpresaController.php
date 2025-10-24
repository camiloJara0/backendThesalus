<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresa = Empresa::where('estado', 1)->get();
        
        return response()->json([
            'success' => true,
            'data' => $empresa
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
        // Validación de los campos


        // Crear la empresa campo por campo
        $empresa = new Empresa();
        $empresa->nombre = $request->nombre;
        $empresa->no_identificacion = $request->no_identificacion;
        $empresa->DV = $request->DV;
        $empresa->direccion = $request->direccion;
        $empresa->municipio = $request->municipio;
        $empresa->pais = $request->pais;
        $empresa->telefono = $request->telefono;
        $empresa->lenguaje = $request->lenguaje;
        $empresa->tipoDocumento = $request->tipoDocumento;
        $empresa->tipoEntorno = $request->tipoEntorno;
        $empresa->tipoMoneda = $request->tipoMoneda;
        $empresa->tipoOperacion = $request->tipoOperacion;
        $empresa->tipoOrganizacion = $request->tipoOrganizacion;
        $empresa->tipoRegimen = $request->tipoRegimen;
        $empresa->tipoResponsabilidad = $request->tipoResponsabilidad;
        $empresa->impuesto = $request->impuesto;
        $empresa->registroMercantil = $request->registroMercantil;
        $empresa->logo = $request->logo;
        $empresa->logoLogin = $request->logoLogin;
        $empresa->JPG = $request->JPG;
        $empresa->save();


        // Retornar respuesta
        return response()->json([
            'message' => 'Empresa creada exitosamente.',
            'data' => $empresa
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        return $empresa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        $empresa->nombre = $request->nombre;
        $empresa->no_identificacion = $request->no_identificacion;
        $empresa->DV = $request->DV;
        $empresa->direccion = $request->direccion;
        $empresa->municipio = $request->municipio;
        $empresa->departamento = $request->departamento;
        $empresa->pais = $request->pais;
        $empresa->telefono = $request->telefono;
        $empresa->lenguaje = $request->lenguaje;
        $empresa->tipoDocumento = $request->tipoDocumento;
        $empresa->tipoEntorno = $request->tipoEntorno;
        $empresa->tipoMoneda = $request->tipoMoneda;
        $empresa->tipoOperacion = $request->tipoOperacion;
        $empresa->tipoOrganizacion = $request->tipoOrganizacion;
        $empresa->tipoRegimen = $request->tipoRegimen;
        $empresa->tipoResponsabilidad = $request->tipoResponsabilidad;
        $empresa->impuesto = $request->impuesto;
        $empresa->registroMercantil = $request->registroMercantil;
        $empresa->logo = $request->logo;
        $empresa->logoLogin = $request->logoLogin;
        $empresa->JPG = $request->JPG;
        $empresa->save();


        // Retornar respuesta
        return response()->json([
            'message' => 'Empresa actualizada exitosamente.',
            'data' => $empresa
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {

        $empresa->estado = 0;
        $empresa->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Empresa creada exitosamente.',
            'data' => $empresa
        ], 201);
    }
}
