<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\InformacionUser;
use App\Models\Eps;
use App\Models\Plan_manejo_procedimiento;
use App\Models\Antecedente;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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

    public function traePacientes()
    {
        $pacientes = Paciente::where('estado', 1)->get();
        $informacionUsers = InformacionUser::get();
        $eps = Eps::where('estado', 1)->get();

        return response()->json([
            'success' => true,
            'pacientes' => $pacientes,
            'informacionUsers' => $informacionUsers,
            'eps' => $eps,
        ], 201);
    }

    public function traeKardex()
    {
        $pacientes = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->select('pacientes.*', 'informacion_users.*', 'eps.nombre as Eps', 'pacientes.id as id_paciente')
            ->get();
        
        $kardex = [];

        foreach ($pacientes as $paciente) {
            // id_historia
            $idHistoria = DB::table('historia__clinicas')
                ->where('id_paciente', $paciente->id_paciente)
                ->value('id');

            // Todos los análisis del paciente
            $analisisList = DB::table('analises')
                ->where('id_historia', $idHistoria)
                ->orderBy('created_at', 'asc')
                ->get();

            // Diagnósticos de todos los análisis
            $diagnosticos = DB::table('diagnosticos')
                ->whereIn('id_analisis', $analisisList->pluck('id'))
                ->pluck('descripcion');

            // Equipos de todos los análisis
            $equipos = DB::table('plan_manejo_equipos')
                ->whereIn('id_analisis', $analisisList->pluck('id'))
                ->pluck('descripcion');

            $flagsEquipos = [
                'kit_cateterismo'   => $equipos->contains(fn($d) => str_contains(strtolower($d), 'cateterismo')) ? 'Si' : 'No',
                'kit_sonda'         => $equipos->contains(fn($d) => str_contains(strtolower($d), 'sonda')) ? 'Si' : 'No',
                'kit_gastro'        => $equipos->contains(fn($d) => str_contains(strtolower($d), 'gastro')) ? 'Si' : 'No',
                'traqueo'           => $equipos->contains(fn($d) => str_contains(strtolower($d), 'traqueo')) ? 'Si' : 'No',
                'oxigeno'           => $equipos->contains(fn($d) => str_contains(strtolower($d), 'oxigeno')) ? 'Si' : 'No',
                'vm'                => $equipos->contains(fn($d) => str_contains(strtolower($d), 'ventilador')) ? 'Si' : 'No',
                'equipos_biomedicos'=> $equipos->contains(fn($d) => str_contains(strtolower($d), 'equipos biomedicos')) ? 'Si' : 'No',
            ];

            // Servicios de todos los análisis
            $serviciosAnalisis = DB::table('analises')
                ->join('servicio', 'analises.id_servicio', '=', 'servicio.id')
                ->join('informacion_users', 'analises.id_medico', '=', 'informacion_users.id')
                ->whereIn('analises.id', $analisisList->pluck('id'))
                ->select('servicio.name as servicio', 'informacion_users.name as medico', 'analises.created_at as created_at')
                ->get();

            // Buscar el último análisis con servicio de nutrición
            $ultimaNutricion = $serviciosAnalisis
                ->filter(fn($s) => str_contains(strtolower($s->servicio), 'nutricion'))
                ->last();

            // Si existe, formatear el mes; si no, devolver "N/A"
            $nutricionistaMes = $ultimaNutricion
                ? Carbon::parse($ultimaNutricion->created_at)->locale('es')->translatedFormat('F')
                : 'N/A';

            // Buscar el último análisis con servicio de nutrición
            $ultimaPsicologia = $serviciosAnalisis
                ->filter(fn($s) => str_contains(strtolower($s->servicio), 'psicologia'))
                ->last();

            // Si existe, formatear el mes; si no, devolver "N/A"
            $psicologiaMes = $ultimaPsicologia
                ? Carbon::parse($ultimaPsicologia->created_at)->locale('es')->translatedFormat('F')
                : 'N/A';

            $flagsServicios = [
                // Respiratoria
                'terapia_respiratoria' => $serviciosAnalisis
                    ->filter(fn($s) => str_contains(strtolower($s->servicio), 'respiratoria'))
                    ->count(),
                'terapeuta_respiratoria' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'respiratoria'))
                )->medico ?? 'N/A',

                // Física
                'terapia_fisica' => $serviciosAnalisis
                    ->filter(fn($s) => str_contains(strtolower($s->servicio), 'fisica'))
                    ->count(),
                'terapeuta_fisica' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'fisica'))
                )->medico ?? 'N/A',

                // Fonoaudiología
                'terapia_fonoaudiologia' => $serviciosAnalisis
                    ->filter(fn($s) => str_contains(strtolower($s->servicio), 'FONOAUDIOLOGIA'))
                    ->count(),
                'terapeuta_fonoaudiologia' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'FONOAUDIOLOGIA'))
                )->medico ?? 'N/A',

                // Ocupacional
                'terapia_ocupacional' => $serviciosAnalisis
                    ->filter(fn($s) => str_contains(strtolower($s->servicio), 'ocupacional'))
                    ->count(),
                'terapeuta_ocupacional' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'ocupacional'))
                )->medico ?? 'N/A',

                // Nutricionista
                'nutricionista' => $nutricionistaMes,
                'profesional_nutricionista' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'nutricion'))
                )->medico ?? 'N/A',

                // Psicología
                'psicologia' => $psicologiaMes,
                'profesional_psicologia' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'psicologia'))
                )->medico ?? 'N/A',

                // Trabajo social
                'trabajo_social' => $serviciosAnalisis
                    ->filter(fn($s) => str_contains(strtolower($s->servicio), 'social'))
                    ->count(),
                'profesional_trabajo_social' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'social'))
                )->medico ?? 'N/A',

                // Guía espiritual
                'guia_espiritual' => $serviciosAnalisis
                    ->filter(fn($s) => str_contains(strtolower($s->servicio), 'espiritual'))
                    ->count(),
                'profesional_guia_espiritual' => optional(
                    $serviciosAnalisis->first(fn($s) => str_contains(strtolower($s->servicio), 'espiritual'))
                )->medico ?? 'N/A',

            ];


            // Fecha última visita médica = último análisis
            $fechaUltimaVisita = optional($analisisList->last())->created_at;
            $pacienteArray = (array) $paciente;

            $kardex[] = [
                ...$pacienteArray,
                'diagnostico' => $diagnosticos->implode(', '),
                ...$flagsEquipos,
                ...$flagsServicios,
                'fecha_ultima_visita' => $fechaUltimaVisita,
            ];
        }


        return response()->json([
            'success' => true,
            'data' => $kardex,
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
        $data = $request->all();

        // 1️⃣ Buscar o crear el usuario
        $informacionUser = InformacionUser::where('No_document', $request->No_document)->first();

        if(!$informacionUser){
            // 2️⃣ Guardar información adicional en InformacionUser
            $informacionUser = new InformacionUser();
            $informacionUser->name = $request->name;
            $informacionUser->No_document = $request->No_document;
            $informacionUser->type_doc = $request->type_doc;
            $informacionUser->celular = $request->celular;
            $informacionUser->telefono = $request->telefono ?? null;
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
        $paciente->regimen = $request->regimen;
        $paciente->vulnerabilidad = $request->vulnerabilidad;
        $paciente->save();


            foreach ($data['Plan_manejo_procedimientos'] ?? [] as $plan_procedimiento) {
                $nuevo = Plan_manejo_procedimiento::create([...$plan_procedimiento, 'id_paciente' => $paciente->id]);
            }


            foreach ($data['Antecedentes'] ?? [] as $antecedente) {
                $nuevo = Antecedente::create([...$antecedente, 'id_paciente' => $paciente->id]);
            }

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
        $data = $request->all();
        
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
            $paciente->regimen = $request->regimen;
            $paciente->vulnerabilidad = $request->vulnerabilidad;
            $paciente->save();
        }

            foreach ($data['Plan_manejo_procedimientos'] ?? [] as $plan_procedimiento) {
                $nuevo = Plan_manejo_procedimiento::create([...$plan_procedimiento, 'id_paciente' => $paciente->id]);
            }


            foreach ($data['Antecedentes'] ?? [] as $antecedente) {
                $nuevo = Antecedente::create([...$antecedente, 'id_paciente' => $paciente->id]);
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

            // Cancelar citas inactivas del paciente
            Cita::where('id_paciente', $paciente->id)
                ->where('estado', 'Inactiva')
                ->update([
                    'estado' => 'cancelada',
                    'motivo_cancelacion' => 'Paciente eliminado',
                ]);
        }

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Paciente deshabilitado exitosamente.',
            'data' => $paciente
        ], 200);
    }
}
