<?php

namespace App\Http\Controllers;

use App\Models\Profesion;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Profesionals;
use App\Models\Profesional_has_permisos;
use App\Models\Secciones;
use Illuminate\Http\Request;
use App\Mail\SolicitudPermisoMail;

class ProfesionalHasPermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permiso = Profesion::where('estado', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $permiso
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
        DB::beginTransaction();

        try {
            // 1️⃣ Crear nuevo permiso

            if (!empty($request->permisos) && is_array($request->permisos)) {
                // 1️⃣ Obtener profesional
                $profesional = DB::table('profesionals')
                    ->where('id', $request->id_profesional)
                    ->first();

                if (!$profesional) {
                    throw new \Exception("Profesional no encontrado");
                }

                // 2️⃣ Permisos que ya tiene por profesión
                $permisosProfesion = DB::table('profesions_has_permisos')
                    ->where('id_profesion', $profesional->id_profesion)
                    ->pluck('id_seccion')
                    ->toArray();

                // 3️⃣ Obtener IDs de las secciones solicitadas
                $seccionesSolicitadas = DB::table('secciones')
                    ->whereIn('nombre', $request->permisos)
                    ->pluck('id')
                    ->toArray();

                // 4️⃣ Filtrar solo los que NO tiene por profesión
                $permisosAInsertar = array_diff($seccionesSolicitadas, $permisosProfesion);

                // 5️⃣ Insertar solo los válidos
                foreach ($permisosAInsertar as $idSeccion) {
                    DB::table('profesional_has_permisos')->insert([
                        'id_profesional' => $request->id_profesional,
                        'id_seccion'     => $idSeccion,
                        'fecha_inicio'   => now(),
                        'fecha_fin'      => $request->fecha_fin ?? null,
                    ]);
                }
            };

            DB::commit();

            // 3️⃣ Retornar respuesta
            return response()->json([
                'success' => true,
                'message' => 'Permisos para profesional creadas exitosamente.',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la permisos al profesional.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function show(Profesion $profesion)
    {
        return $profesion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profesion $profesion)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Crear nuevo permiso
            $profesional = Profesional::where('id', $request->id_profesional)->first();

            // 2️⃣ Asociar permisos si vienen en el request
            // 2️⃣ Obtener IDs de permisos desde nombres
            $permisosIds = [];
            if (!empty($request->permisos) && is_array($request->permisos)) {
                $permisosIds = Secciones::whereIn('nombre', $request->permisos)->pluck('id')->toArray();
            }

            // 3️⃣ Sincronizar permisos (agrega nuevos y elimina los que no están)
            $profesional->permisos()->sync($permisosIds);

            DB::commit();

            // 3️⃣ Retornar respuesta
            return response()->json([
                'success' => true,
                'message' => 'Permisos para profesional creadas exitosamente.',
                'data' => $profesion
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la permisos al profesional.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profesion $profesion)
    {
        $profesion->estado = 0;
        $profesion->save();
        response()->json([
            'message' => 'Profesión desactivada exitosamente.'
        ]);

    }

    public function solicitarPermiso(Request $request)
    {
        DB::beginTransaction();

        try {

            // $seccion = Secciones::where('nombre', $request->nombre_seccion)->first();

            // if (!$seccion) {
            //     throw new \Exception("Sección no encontrada");
            // }

            $token = Str::uuid();

            DB::table('solicitud_permisos')->insert([
                'id_profesional'     => $request->id_profesional,
                'id_seccion'         => $request->id_seccion,
                'token_aprobacion'   => $token,
                'estado'             => 'pendiente',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // Enviar correo al admin
            $link = rtrim(env('APP_URL'), '/') . "/aprobar-permiso?token={$token}";

            Mail::to('camilojara0000@gmail.com')->send(new SolicitudPermisoMail($link));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud enviada al administrador'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function aprobarPermiso(Request $request)
    {
        DB::beginTransaction();

        try {

            $solicitud = DB::table('solicitud_permisos')
                ->where('token_aprobacion', $request->token)
                ->where('estado', 'pendiente')
                ->first();

            if (!$solicitud) {
                throw new \Exception("Solicitud inválida o ya usada");
            }

            DB::table('profesional_has_permisos')->insert([
                'id_profesional' => $solicitud->id_profesional,
                'id_seccion'     => $solicitud->id_seccion,
                'fecha_inicio'   => now(),
                'usado'          => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('solicitud_permisos')
                ->where('id', $solicitud->id)
                ->update([
                    'estado' => 'aprobado'
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permiso otorgado correctamente'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function verificarPermisos(Request $request)
    {
        $permisos = DB::table('profesional_has_permisos')
            ->join('secciones', 'profesional_has_permisos.id_seccion', '=', 'secciones.id')
            ->where('id_profesional', $request->id_profesional)
            ->where('usado', 0)
            ->select(
                'profesional_has_permisos.id as permiso_id',
                'secciones.nombre',
                'secciones.id as id_seccion'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $permisos
        ]);
    }

    public function consumirPermiso(Request $request)
    {
        DB::table('profesional_has_permisos')
            ->where('id', $request->permiso_id)
            ->update([
                'usado' => 1,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true
        ]);
    }
}
