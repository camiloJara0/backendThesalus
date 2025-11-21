<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Request\Login;
use App\Models\InformacionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodigoVerificacionMail;
use App\Models\CodigoVerificacion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with(['empresa'])->where('estado', 1)->get();
    }

    public function administradores()
    {
        $administradores = User::where('rol', 'Admin')
            ->join('informacion_users', 'informacion_users.id', '=', 'users.id_infoUsuario')
            ->select('users.correo', 'informacion_users.*')
            ->get();

        return response()->json([
            "success" => true,
            "data" => $administradores
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
        // 1️⃣ Buscar o crear el usuario
        $informacionUser = InformacionUser::where('No_document', $request->No_document)->first();
        $user = $informacionUser ? User::where('id_infoUsuario', $informacionUser->id)->first() : null;


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

        if(!$user){
            // guardar user si no existe
            $user = new User();
            $user->id_empresa = 1;
            $user->id_infoUsuario = $informacionUser->id;;
            $user->correo = $request->correo;
            $user->contraseña = Hash::make($request->contraseña);
            $user->rol = 'Admin';
            $user-> save();
        }
        
        // 4️⃣ Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente.',
            'informacion' => $informacionUser,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->empresa = $user->Empresa;
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->id_empresa = $request->id_empresa;
        $user->correo = $request->correo;
        if(isset($request->contraseña)){
            if(!empty($request->contraseña)){
                $user->contraseña = Hash::make($request->contraseña);
            }
        }
        $user->rol = $request->rol;
        $user->save();

        // Retornar respuesta
        return response()->json([
            'message' => 'Usuario actualizado exitosamente.',
            'data' => $user
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->estado = 0;
        $user->save();
        response()->json([
            'message' => 'User desactivado exitosamente.'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required',
        ]);

        $user = User::where('correo', $request->correo)->first();

        if (!$user || !Hash::check($request->contraseña, $user->contraseña)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $tokenResult = $user->createToken('auth_token');
        
        // Establece la expiración
        $accessToken = $tokenResult->accessToken;
        $accessToken->expires_at = now()->addHours(7);
        $accessToken->save();

        // Obtén el token en texto plano
        $token = $tokenResult->plainTextToken;

        // Obtener información adicional del usuario
        $infoUsuario = InformacionUser::find($user->id_infoUsuario);

        $permisos = [];

        if ($user->rol === 'Profesional') {
            // Obtener la profesión del usuario
            $profesional = DB::table('profesionals')
                ->where('id_infoUsuario', $user->id_infoUsuario)
                ->first();

            if ($profesional) {
                // Obtener los permisos asociados a la profesión
                $permisos = DB::table('profesions_has_permisos')
                    ->join('secciones', 'profesions_has_permisos.id_seccion', '=', 'secciones.id')
                    ->where('profesions_has_permisos.id_profesion', $profesional->id_profesion)
                    ->pluck('secciones.nombre'); // o cualquier campo que represente el permiso
            } else {
                // Si no tiene profesión, no tiene permisos
                $permisos = collect(); // colección vacía
            }

        } elseif ($user->rol == 'Admin') {
            $permisos = DB::table('secciones')->pluck('nombre');
        } else {
            $permisos = collect(); // colección vacía
        }


        return response()->json([
            'message' => 'Login exitoso',
            'access_token' => $token,
            'user' => [
                'correo' => $user->correo,
                'rol' => $user->rol,
                'usuario' => $infoUsuario,
                'permisos' => $permisos
            ]
        ]);


    }

    public function verificacion(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

        $usuario = User::where('correo', $request->correo)->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Correo no registrado'
            ]);
        }

        $codigo = Str::random(6);

        CodigoVerificacion::create([
            'correo' => $usuario->correo,
            'codigo' => $codigo,
            'expira_en' => Carbon::now()->addMinutes(15)
        ]);

        Mail::to($usuario->correo)->send(new CodigoVerificacionMail($usuario->correo, $codigo));

        return response()->json([
            'success' => true,
            'message' => 'Correo enviado con código de verificación'
        ]);
    }

    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'codigo' => 'required|string',
            'contraseña' => 'required|min:6'
        ]);

        $registro = CodigoVerificacion::where('correo', $request->correo)
            ->where('codigo', $request->codigo)
            ->where('usado', false)
            ->where('expira_en', '>', now())
            ->first();

        if (!$registro) {
            return response()->json(['message' => 'Código inválido o expirado'], 401);
        }

        $usuario = User::where('correo', $request->correo)->first();
        $usuario->contraseña = Hash::make($request->contraseña);
        $usuario->save();

        $registro->usado = true;
        $registro->save();

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
    }

    public function verificarUsuario(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

        $usuario = User::where('correo', $request->correo)->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Correo no registrado'
            ]);
        }

        if($usuario->contraseña == null){

            return response()->json([
                'success' => true,
                'primer_ingreso' => true,
                'message' => 'Profesional primer ingreso.'
            ]);
        }

        return response()->json([
            'success' => true,
            'primer_ingreso' => false,
            'message' => 'Usuario ya tiene contraseña registrada.'
        ]);

    }

}
