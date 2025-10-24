<?php

namespace App\Http\Controllers;

use App\Models\InformacionUser;
use Illuminate\Http\Request;

class InformacionUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InformacionUser::with('estado', 1)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $informacionUser = new informacionUser();
        $informacionUser->id_usuario = $request->id_usuario;
        $informacionUser->name = $request->name;
        $informacionUser->No_document = $request->No_document;
        $informacionUser->type_doc = $request->type_doc;
        $informacionUser->celular = $request->celular;
        $informacionUser->telefono = $request->telefono;
        $informacionUser->nacimiento = $request->nacimiento;
        $informacionUser->direccion = $request->direccion;
        $informacionUser->municipio = $request->municipio;
        $informacionUser->departamento = $request->departamento;
        $informacionUser->barrio = $request->barrio;
        $informacionUser->zona = $request->zona;
        $informacionUser->save();

        // Respuesta
        return response()->json([
            'message' => 'Información del usuario creada exitosamente.',
            'data' => $info
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InformacionUser  $informacionUser
     * @return \Illuminate\Http\Response
     */
    public function show(InformacionUser $informacionUser)
    {
        return $informacionUser;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InformacionUser  $informacionUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InformacionUser $informacionUser)
    {
        $informacionUser->name = $request->name;
        $informacionUser->No_document = $request->No_document;
        $informacionUser->type_doc = $request->type_doc;
        $informacionUser->celular = $request->celular;
        $informacionUser->telefono = $request->telefono;
        $informacionUser->nacimiento = $request->nacimiento;
        $informacionUser->direccion = $request->direccion;
        $informacionUser->municipio = $request->municipio;
        $informacionUser->departamento = $request->departamento;
        $informacionUser->barrio = $request->barrio;
        $informacionUser->zona = $request->zona;
        $informacionUser->save();

        // Respuesta
        return response()->json([
            'message' => 'Información del usuario actualizada exitosamente.',
            'data' => $info
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InformacionUser  $informacionUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(InformacionUser $informacionUser)
    {
        //
    }
}
