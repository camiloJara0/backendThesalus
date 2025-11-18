<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\InformacionUser;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paciente = Paciente::where('estado', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $paciente
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1️⃣ Buscar o crear el usuario
        $informacionUser = InformacionUser::where('No_document', $request->No_document)->first();

        if(!$informacionUser){
            // 2️⃣ Guardar información adicional en InformacionUser
            $informacionUser = new InformacionUser();
            $informacionUser->name = $request->name;
            $informacionUser->No_document = $request->No_document;
            $informacionUser->type_doc = $request->type_doc;
            $informacionUser->celular = $request->celular;
            $informacionUser->telefono = $request->telefono || null;
            $informacionUser->nacimiento = $request->nacimiento;
            $informacionUser->direccion = $request->direccion;
            $informacionUser->municipio = $request->municipio;
            $informacionUser->departamento = $request->departamento;
            $informacionUser->barrio = $request->barrio;
            $informacionUser->zona = $request->zona;
            $informacionUser->save();
        }

        // 3️⃣ Guardar datos del paciente
        $paciente = new Paciente();
        $paciente->id_infoUsuario = $informacionUser->id;
        $paciente->id_eps = $request->id_eps;
        $paciente->genero = $request->genero;
        $paciente->sexo = $request->sexo;
        $paciente->regimen = $request->Regimen;
        $paciente->vulnerabilidad = $request->vulnerabilidad;
        $paciente->save();

        // 4️⃣ Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Paciente registrado exitosamente.',
            'informacion' => $informacionUser,
            'paciente' => $paciente
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        return $paciente;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paciente $paciente)
    {
        // Actualizar información del usuario
        $informacionUser = InformacionUser::find($request->id_infoUsuario);

        if ($informacionUser) {
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
        }

        // 2️⃣ Actualizar datos del paciente
        $paciente = Paciente::where('id', $request->id)->first();

        if ($paciente) {
            $paciente->id_eps = $request->id_eps;
            $paciente->genero = $request->genero;
            $paciente->sexo = $request->sexo;
            $paciente->regimen = $request->Regimen;
            $paciente->vulnerabilidad = $request->vulnerabilidad;
            $paciente->save();
        }

        // 3️⃣ Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Paciente actualizado exitosamente.',
            'informacion' => $informacionUser,
            'paciente' => $paciente
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Paciente $paciente)
    {
        // Actualizar información del usuario
        $paciente = Paciente::find($request->id);

        if($paciente){
            $paciente->estado = 0;
            $paciente->save();
        }

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Paciente deshabilitado exitosamente.',
            'data' => $paciente
        ], 200);
    }
}
