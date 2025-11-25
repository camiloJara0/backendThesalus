<?php

namespace App\Http\Controllers;

use App\Models\Profesional;
use App\Models\InformacionUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfesionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profesional = Profesional::where('estado', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $profesional
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
        $usuario = $informacionUser ? User::where('id_infoUsuario', $informacionUser->id)->first() : null;


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

        // 3️⃣ Guardar datos del profesional
        $profesional = new Profesional();
        $profesional->id_infoUsuario = $informacionUser->id;
        $profesional->id_profesion = $request->id_profesion;
        $profesional->zona_laboral = $request->zona_laboral;
        $profesional->departamento_laboral = $request->departamento_laboral;
        $profesional->municipio_laboral = $request->municipio_laboral;
        $profesional->save();

        if(!$usuario){
            // guardar usuario si no existe
            $usuario = new User();
            $usuario->id_empresa = 1;
            $usuario->id_infoUsuario = $informacionUser->id;;
            $usuario->correo = $request->correo;
            $usuario->contraseña = null;
            $usuario->rol = 'Profesional';
            $usuario-> save();
        }
        
        // 4️⃣ Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Profesional registrado exitosamente.',
            'informacion' => $informacionUser,
            'profesional' => $profesional
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profesional  $profesional
     * @return \Illuminate\Http\Response
     */
    public function show(Profesional $profesional)
    {
        return $profesional;
    }

    public function showPorDocumento($no_document)
    {
        // Buscar el usuario a través de informacion_users
        $info = InformacionUser::where('No_document', $no_document)->first();

        if (!$info) {
            return response()->json(['message' => 'Documento no encontrado.'], 404);
        }

        // Buscar el profesional asociado al usuario
        $profesional = Profesional::where('id_usuario', $info->id_usuario)->first();

        if (!$profesional) {
            return response()->json(['message' => 'Profesional no encontrado.'], 404);
        }

        return response()->json($profesional);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profesional  $profesional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profesional $profesional)
    {
        // 1️⃣ Buscar el usuario y su información
        $informacionUser = InformacionUser::where('No_document', $request->No_document)->first();
        $usuario = $informacionUser ? User::where('id_infoUsuario', $informacionUser->id)->first() : null;

        if ($informacionUser) {
            // 2️⃣ Actualizar información adicional en InformacionUser
            $informacionUser->name = $request->name;
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

        // 3️⃣ Actualizar o crear datos del profesional
        $profesional = Profesional::where('id_infoUsuario', $informacionUser->id)->first();
        if ($profesional) {
            $profesional->id_profesion = $request->id_profesion;
            $profesional->zona_laboral = $request->zona_laboral;
            $profesional->departamento_laboral = $request->departamento_laboral;
            $profesional->municipio_laboral = $request->municipio_laboral;
            $profesional->save();
        }

        // 4️⃣ Actualizar correo del usuario si existe
        if ($usuario) {
            $usuario->correo = $request->correo;
            $usuario->save();
        }

        // 5️⃣ Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Datos del profesional actualizados exitosamente.',
            'informacion' => $informacionUser,
            'profesional' => $profesional
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profesional  $profesional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Profesional $profesional)
    {
        $profesional = Profesional::find($request->id);

        if($profesional){
            $profesional->estado = 0;
            $profesional->save();
        }

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Profesional deshabilitado exitosamente.',
            'data' => $profesional
        ], 200);
    }
}
