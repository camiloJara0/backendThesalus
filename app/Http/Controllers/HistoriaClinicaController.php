<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Historia_Clinica;
use App\Models\Analisis;
use App\Models\Diagnostico;
use App\Models\Diagnostico_relacionado;
use App\Models\Antecedente;
use App\Models\Enfermedad;
use App\Models\Examen_fisico;
use App\Models\Plan_manejo_medicamento;
use App\Models\Plan_manejo_procedimiento;
use App\Models\Plan_manejo_insumo;
use App\Models\Plan_manejo_equipo;
use App\Models\Cita;
use App\Models\Terapia;
use App\Models\Nota;
use App\Models\Descripcion_nota;
use App\Models\Movimiento;
use App\Models\Insumo;

use Illuminate\Http\Request;

class HistoriaClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $historia = Historia_Clinica::get();

        return response()->json([
            'success' => true,
            'data' => $historia
        ]);
    }

    public function traeDatosHistoria()
    {
        $historias = Historia_Clinica::get();
        $analisis = Analisis::get();
        $terapias = Terapia::get();
        $notas = Nota::get();
        $enfermedades = Enfermedad::get();
        $antecedentes = Antecedente::get();
        $examenes_fisicos = Examen_fisico::get();
        $descripciones_notas = Descripcion_nota::get();

        return response()->json([
            'success' => true,
            'historias' => $historias,
            'analisis' => $analisis,
            'terapias' => $terapias,
            'notas' => $notas,
            'enfermedades' => $enfermedades,
            'antecedentes' => $antecedentes,
            'examenes_fisicos' => $examenes_fisicos,
            'descripciones_notas' => $descripciones_notas
        ]); 
    }

    public function traeDatosDiagnosticos()
    {
        $diagnosticos = Diagnostico::get();
        $diagnosticosRelacionados = Diagnostico_relacionado::get();

        return response()->json([
            'success' => true,
            'data' => $historia
        ]); 
    }

    public function traeDatosPlanManejo()
    {
        $medicamentos = Plan_manejo_medicamento::get();
        $procedimientos = Plan_manejo_procedimiento::get();
        $insumos = Plan_manejo_insumo::get();
        $equipos = Plan_manejo_equipo::get();


        return response()->json([
            'success' => true,
            'medicamentos' => $medicamentos,
            'procedimientos' => $procedimientos,
            'insumos' => $insumos,
            'equipos' => $equipos,
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
            $data = $request->all();
            $ids = [];

            $historia = Historia_Clinica::where('id_paciente', $request->HistoriaClinica['id_paciente'])->first();
            
            // 1️⃣ Guardar Historia Clínica
            if(!$historia){
                $historia = Historia_Clinica::create($data['HistoriaClinica']);
            }
            $ids['HistoriaClinica'] = $historia->id;

            // 2️⃣ Guardar Análisis con id_historia
            $data['Analisis']['id_historia'] = $historia->id;
            $analisis = Analisis::create($data['Analisis']);
            $ids['Analisis'] = $analisis->id;

            $ids['Diagnosticos'] = [];
            foreach ($data['Diagnosticos'] ?? [] as $diagnostico) {
                $nuevo = Diagnostico::create([...$diagnostico, 'id_analisis' => $analisis->id]);
                $ids['Diagnosticos'][] = $nuevo->id;
            }

            $ids['Antecedentes'] = [];
            foreach ($data['Antecedentes'] ?? [] as $antecedente) {
                $nuevo = Antecedente::create([...$antecedente]);
                $ids['Antecedentes'][] = $nuevo->id;
            }


            if (!empty($data['Enfermedad'])) {
                $enfermedad = Enfermedad::create([
                    ...$data['Enfermedad'],
                    'id_analisis' => $analisis->id,
                ]);
                $ids['Enfermedad'] = $enfermedad->id;
            }

            if (!empty($data['ExamenFisico'])) {
                $examen = $data['ExamenFisico'];
                $signos = $examen['signosVitales'] ?? [];

                $examenFisico = Examen_fisico::create([
                    'peso' => $examen['peso'],
                    'altura' => $examen['altura'],
                    'otros' => $examen['otros'],
                    'id_analisis' => $analisis->id,
                    'signosVitales' => $signos
                ]);

                $ids['ExamenFisico'] = $examenFisico->id;
            }

            foreach (['Plan_manejo_medicamentos' => Plan_manejo_medicamento::class,
                      'Plan_manejo_insumos' => Plan_manejo_insumo::class,
                      'Plan_manejo_equipos' => Plan_manejo_equipo::class] as $key => $model) {
                if (!empty($data[$key])) {
                    $ids[$key] = [];
                    foreach ($data[$key] as $item) {
                        $nuevo = $model::create([
                            ...$item,
                            'id_analisis' => $analisis->id,
                        ]);
                        $ids[$key][] = $nuevo->id;

                        // Definir cantidadMovimiento según el tipo de plan
                        $cantidadMovimiento = match ($key) {
                            'Plan_manejo_equipos'      => $item['usado'] ? 1 : 0,
                            default                    => $item['cantidad'] ?? 0,
                        };

                        // Crear Movimiento
                        Movimiento::create([
                            'cantidadMovimiento' => $cantidadMovimiento,
                            'fechaMovimiento'    => now(),
                            'tipoMovimiento'     => 'Egreso',
                            'id_medico'          => $data['Analisis']['id_medico'] ?? null,
                            'id_analisis'        => $analisis->id,
                            'id_insumo'          => $item['id_insumo'],
                        ]);

                        // Actualizar stock del insumo
                        $insumo = Insumo::findOrFail($item['id_insumo']);
                        $insumo->stock -= $cantidadMovimiento;
                        $insumo->save();

                    }
                }
            }


            if (!empty($data['Plan_manejo_procedimientos'])) {
                $ids['Plan_manejo_procedimientos'] = [];
                    foreach ($data['Plan_manejo_procedimientos'] as $item) {
                        $nuevo = Plan_manejo_procedimiento::create([
                            ...$item,
                        ]);
                        $ids['Plan_manejo_procedimientos'][] = $nuevo->id;
                    }
                }

            if (!empty($data['Terapia'])) {
                $terapia = Terapia::create($data['Terapia']);
                $ids['Terapia'] = $terapia->id;
            }

            // 4️⃣ Actualizar estado de la Cita
            if (!empty($data['Cita'])) {
                Cita::where('id', $data['Cita']['id'] ?? null)
                    ->update([
                        'estado' => 'Realizada',
                        'id_examen_fisico' => $analisis->id
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'ids' => $ids, 
                'Historia:' => $historia,
                'Analisis' => $analisis
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar historia clínica', 'message' => $e->getMessage()], 500);
        }

    }

    public function storeNutricion(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $ids = [];

            $historia = Historia_Clinica::where('id_paciente', $request->HistoriaClinica['id_paciente'])->first();
            
            // 1️⃣ Guardar Historia Clínica
            if(!$historia){
                $historia = Historia_Clinica::create($data['HistoriaClinica']);
            }
            $ids['HistoriaClinica'] = $historia->id;

            // 2️⃣ Guardar Análisis con id_historia
            $data['Analisis']['id_historia'] = $historia->id;
            $analisis = Analisis::create($data['Analisis']);
            $ids['Analisis'] = $analisis->id;

            $ids['Diagnosticos'] = [];
            foreach ($data['Diagnosticos'] ?? [] as $diagnostico) {
                $nuevo = Diagnostico::create([...$diagnostico, 'id_analisis' => $analisis->id]);
                $ids['Diagnosticos'][] = $nuevo->id;
            }

            // 4️⃣ Actualizar estado de la Cita
            if (!empty($data['Cita'])) {
                Cita::where('id', $data['Cita']['id'] ?? null)
                    ->update([
                        'estado' => 'Realizada',
                        'id_examen_fisico' => $analisis->id
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'ids' => $ids, 
                'Historia:' => $historia,
                'Analisis' => $analisis
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar historia clínica', 'message' => $e->getMessage()], 500);
        }

    }

    public function storeTrabajoSocial(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $ids = [];

            $historia = Historia_Clinica::where('id_paciente', $request->HistoriaClinica['id_paciente'])->first();
            
            // 1️⃣ Guardar Historia Clínica
            if(!$historia){
                $historia = Historia_Clinica::create($data['HistoriaClinica']);
            }
            $ids['HistoriaClinica'] = $historia->id;

            // 2️⃣ Guardar Análisis con id_historia
            $data['Analisis']['id_historia'] = $historia->id;
            $analisis = Analisis::create($data['Analisis']);
            $ids['Analisis'] = $analisis->id;

            $ids['Diagnosticos'] = [];
            foreach ($data['Diagnosticos'] ?? [] as $diagnostico) {
                $nuevo = Diagnostico::create([...$diagnostico, 'id_analisis' => $analisis->id]);
                $ids['Diagnosticos'][] = $nuevo->id;
            }

            foreach (['Plan_manejo_medicamentos' => Plan_manejo_medicamento::class,
                      'Plan_manejo_insumos' => Plan_manejo_insumo::class,
                      'Plan_manejo_equipos' => Plan_manejo_equipo::class] as $key => $model) {
                if (!empty($data[$key])) {
                    $ids[$key] = [];
                    foreach ($data[$key] as $item) {
                        $nuevo = $model::create([
                            ...$item,
                            'id_analisis' => $analisis->id,
                        ]);
                        $ids[$key][] = $nuevo->id;

                        // Definir cantidadMovimiento según el tipo de plan
                        $cantidadMovimiento = match ($key) {
                            'Plan_manejo_equipos'      => $item['usado'] ? 1 : 0,
                            default                    => $item['cantidad'] ?? 0,
                        };

                        // Crear Movimiento
                        Movimiento::create([
                            'cantidadMovimiento' => $cantidadMovimiento,
                            'fechaMovimiento'    => now(),
                            'tipoMovimiento'     => 'Egreso',
                            'id_medico'          => $data['Analisis']['id_medico'] ?? null,
                            'id_analisis'        => $analisis->id,
                            'id_insumo'          => $item['id_insumo'],
                        ]);

                        // Actualizar stock del insumo
                        $insumo = Insumo::findOrFail($item['id_insumo']);
                        $insumo->stock -= $cantidadMovimiento;
                        $insumo->save();
                    }
                }
            }


            // if (!empty($data['Plan_manejo_procedimientos'])) {
            //     $ids['Plan_manejo_procedimientos'] = [];
            //         foreach ($data['Plan_manejo_procedimientos'] as $item) {
            //             $nuevo = Plan_manejo_procedimiento::create([
            //                 ...$item,
            //             ]);
            //             $ids['Plan_manejo_procedimientos'][] = $nuevo->id;
            //         }
            //     }

            // 4️⃣ Actualizar estado de la Cita
            if (!empty($data['Cita'])) {
                Cita::where('id', $data['Cita']['id'] ?? null)
                    ->update([
                        'estado' => 'Realizada',
                        'id_examen_fisico' => $analisis->id
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'ids' => $ids, 
                'Historia:' => $historia,
                'Analisis' => $analisis
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar historia clínica', 'message' => $e->getMessage()], 500);
        }

    }

    public function storeNota(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $ids = [];

            $historia = Historia_Clinica::where('id_paciente', $request->HistoriaClinica['id_paciente'])->first();
            
            // 1️⃣ Guardar Historia Clínica
            if(!$historia){
                $historia = Historia_Clinica::create($data['HistoriaClinica']);
            }
            $ids['HistoriaClinica'] = $historia->id;

            // 2️⃣ Guardar Análisis con id_historia
            $data['Analisis']['id_historia'] = $historia->id;
            $analisis = Analisis::create($data['Analisis']);
            $ids['Analisis'] = $analisis->id;

            $ids['Diagnosticos'] = [];
            foreach ($data['Diagnosticos'] ?? [] as $diagnostico) {
                $nuevo = Diagnostico::create([...$diagnostico, 'id_analisis' => $analisis->id]);
                $ids['Diagnosticos'][] = $nuevo->id;
            }
            // Crear la nueva nota
            $nota = new Nota();
            $nota->id_paciente = $request->Nota['id_paciente'];
            $nota->id_procedimiento = null;
            $nota->id_profesional = $request->Nota['id_profesional'];
            $nota->direccion = $request->Nota['direccion'];
            $nota->fecha_nota = $request->Nota['fecha_nota'];
            $nota->hora_nota = $request->Nota['hora_nota'];
            $nota->nota = $request->Nota['nota'] ?? 'nota';
            $nota->tipoAnalisis = $request->Nota['tipoAnalisis'];
            $nota->id_analisis = $analisis->id;
            $nota->save();

            $ids['Descripcion'] = [];
            foreach ($data['Descripcion'] ?? [] as $descripcion) {
                $nuevo = Descripcion_nota::create([...$descripcion, 'id_nota' => $nota->id]);
                $ids['Descripcion'][] = $nuevo->id;
            }

            // 4️⃣ Actualizar estado de la Cita
            if (!empty($data['Cita'])) {
                Cita::where('id', $data['Cita']['id'] ?? null)
                    ->update([
                        'estado' => 'Realizada',
                        'id_examen_fisico' => null
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'ids' => $ids,
                'data' => $nota,
                'Historia:' => $historia
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar historia clínica', 'message' => $e->getMessage()], 500);
        }

    }

    // public function storeConsultaPut(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $data = $request->all();
    //         $ids = [];

    //         $analisis = Analisis::where()


    //         // Crear la nueva nota
    //         $nota = new Nota();
    //         $nota->id_paciente = $request->Nota['id_paciente'];
    //         $nota->id_procedimiento = null;
    //         $nota->id_profesional = $request->Nota['id_profesional'];
    //         $nota->direccion = $request->Nota['direccion'];
    //         $nota->fecha_nota = $request->Nota['fecha_nota'];
    //         $nota->hora_nota = $request->Nota['hora_nota'];
    //         $nota->nota = $request->Nota['nota'];
    //         $nota->tipoAnalisis = $request->Nota['tipoAnalisis'];
    //         $nota->save();

    //         DB::commit();

    //         return response()->json([
    //             'success' => true, 
    //             'ids' => $ids,
    //             'data' => $nota,
    //             'Historia:' => $historia
    //         ], 201);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['error' => 'Error al guardar historia clínica', 'message' => $e->getMessage()], 500);
    //     }

    // }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Historia_Clinica  $historia_Clinica
     * @return \Illuminate\Http\Response
     */
    public function show(Historia_Clinica $historia_Clinica)
    {
        return $historia_Clinica;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Historia_Clinica  $historia_Clinica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Historia_Clinica $historia_Clinica)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $ids = [];

            // 1️⃣ Historia Clínica
            $historia = Historia_Clinica::firstOrCreate(
                ['id_paciente' => $data['HistoriaClinica']['id_paciente']],
                $data['HistoriaClinica']
            );
            $ids['HistoriaClinica'] = $historia->id;

            // 2️⃣ Análisis
            $data['Analisis']['id_historia'] = $historia->id;
            $analisis = Analisis::updateOrCreate(
                ['id' => $data['Analisis']['id'] ?? null],
                $data['Analisis']
            );
            $ids['Analisis'] = $analisis->id;

            // 3️⃣ Diagnósticos
            if (!empty($data['Diagnosticos'])) {
                $ids['Diagnosticos'] = [];
                foreach ($data['Diagnosticos'] as $diagnostico) {
                    $nuevo = Diagnostico::updateOrCreate(
                        ['id' => $diagnostico['id'] ?? null],
                        [...$diagnostico, 'id_analisis' => $analisis->id]
                    );
                    $ids['Diagnosticos'][] = $nuevo->id;
                }
            }

            // 4️⃣ Antecedentes
            if (!empty($data['Antecedentes'])) {
                $ids['Antecedentes'] = [];
                foreach ($data['Antecedentes'] as $antecedente) {
                    $nuevo = Antecedente::updateOrCreate(
                        ['id' => $antecedente['id'] ?? null],
                        $antecedente
                    );
                    $ids['Antecedentes'][] = $nuevo->id;
                }
            }

            // 5️⃣ Enfermedad
            if (!empty($data['Enfermedad'])) {
                $enfermedad = Enfermedad::updateOrCreate(
                    ['id' => $data['Enfermedad']['id'] ?? null],
                    [...$data['Enfermedad'], 'id_analisis' => $analisis->id]
                );
                $ids['Enfermedad'] = $enfermedad->id;
            }

            // 6️⃣ Examen físico
            if (!empty($data['ExamenFisico'])) {
                $examen = $data['ExamenFisico'];
                $signos = $examen['signosVitales'] ?? [];

                $examenFisico = Examen_fisico::updateOrCreate(
                    ['id' => $examen['id'] ?? null],
                    [
                        'Peso' => $examen['Peso'],
                        'altura' => $examen['altura'],
                        'otros' => $examen['otros'],
                        'id_analisis' => $analisis->id,
                        'signosVitales' => $signos
                    ]
                );
                $ids['ExamenFisico'] = $examenFisico->id;
            }

            // 7️⃣ Planes de manejo
            foreach ([
                'Plan_manejo_medicamentos' => Plan_manejo_medicamento::class,
                'Plan_manejo_insumos' => Plan_manejo_insumo::class,
                'Plan_manejo_equipos' => Plan_manejo_equipo::class
            ] as $key => $model) {
                if (!empty($data[$key])) {
                    $ids[$key] = [];
                    foreach ($data[$key] as $item) {
                        $nuevo = $model::updateOrCreate(
                            ['id' => $item['id'] ?? null],
                            [...$item, 'id_analisis' => $analisis->id]
                        );
                        $ids[$key][] = $nuevo->id;

                        // creación de Movimiento
                        if ($key === 'Plan_manejo_insumos') {
                            Movimiento::create([
                                'cantidadMovimiento' => $item['cantidad'] ?? 0,
                                'fechaMovimiento'    => now(),
                                'tipoMovimiento'     => 'Engreso',
                                'id_medico'          => $data['Analisis']['id_medico'] ?? null,
                                'id_insumo'          => $item['id_insumo'],
                            ]);

                            // Actualizar stock del insumo
                            $insumo = Insumo::findOrFail($item['id_insumo']);
                            $insumo->stock -= $item['cantidad'] ?? 0;
                            $insumo->save();
                        }

                    }
                }
            }



            // 8️⃣ Cita
            if (!empty($data['Cita'])) {
                Cita::where('id', $data['Cita']['id'] ?? null)
                    ->update([
                        'estado' => 'Realizada',
                        'id_examen_fisico' => $analisis->id
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'ids' => $ids,
                'Historia' => $historia
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al guardar/actualizar historia clínica',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Historia_Clinica  $historia_Clinica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Historia_Clinica $historia_Clinica)
    {
        //
    }

    public function imprimirEvolucion($id)
    {
        $analisis = Analisis::find($id);

        $historia = Historia_Clinica::where('id', $analisis->id_historia)->first();

        // Paciente con su información de usuario
        $paciente = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->where('pacientes.id', $historia->id_paciente)
            ->select('pacientes.*', 'informacion_users.*', 'eps.nombre as Eps')
            ->first();

        // Profesional con su información de usuario
        $profesional = DB::table('profesionals')
            ->join('informacion_users', 'profesionals.id_infoUsuario', '=', 'informacion_users.id')
            ->where('profesionals.id', $analisis->id_medico)
            ->select('profesionals.*', 'informacion_users.*')
            ->first();

        // Diagnósticos que coincidan con el id_analisis
        $diagnosticos = DB::table('diagnosticos')
            ->where('id_analisis', $analisis->id)
            ->get();

        $fileName = 'Evolucion_' . $profesional->name . '_' . $analisis->created_at . '.pdf';

        $pdf = Pdf::loadView('pdf.evolucion', compact('paciente','profesional','diagnosticos','analisis'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function imprimirTrabajoSocial($id)
    {
        $analisis = Analisis::find($id);

        $historia = Historia_Clinica::where('id', $analisis->id_historia)->first();

        // Paciente con su información de usuario
        $paciente = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->where('pacientes.id', $historia->id_paciente)
            ->select('pacientes.*', 'informacion_users.*', 'eps.nombre as Eps')
            ->first();

        // Profesional con su información de usuario
        $profesional = DB::table('profesionals')
            ->join('informacion_users', 'profesionals.id_infoUsuario', '=', 'informacion_users.id')
            ->where('profesionals.id', $analisis->id_medico)
            ->select('profesionals.*', 'informacion_users.*')
            ->first();

        // Diagnósticos que coincidan con el id_analisis
        $diagnosticos = DB::table('diagnosticos')
            ->where('id_analisis', $analisis->id)
            ->get();

        $fileName = 'TrabajoSocial_' . $profesional->name . '_' . $analisis->created_at . '.pdf';

        $pdf = Pdf::loadView('pdf.trabajoSocial', compact('paciente','profesional','diagnosticos','analisis'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function imprimirMedicina($id)
    {
        $analisis = Analisis::find($id);

        $historia = Historia_Clinica::where('id', $analisis->id_historia)->first();

        // Paciente con su información de usuario
        $paciente = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->where('pacientes.id', $historia->id_paciente)
            ->select('pacientes.*', 'informacion_users.*', 'eps.nombre as Eps')
            ->first();

        $antecedentes = DB::table('antecedentes')
            ->where('id_paciente', $historia->id_paciente)
            ->get();

        // Profesional con su información de usuario
        $profesional = DB::table('profesionals')
            ->join('informacion_users', 'profesionals.id_infoUsuario', '=', 'informacion_users.id')
            ->where('profesionals.id', $analisis->id_medico)
            ->select('profesionals.*', 'informacion_users.*')
            ->first();

        $examenFisico = DB::table('examen_fisicos')
            ->where('id_analisis', $analisis->id)
            ->first();

        $enfermedades = DB::table('enfermedads')
            ->where('id_analisis', $analisis->id)
            ->first();

        // Diagnósticos que coincidan con el id_analisis
        $diagnosticos = DB::table('diagnosticos')
            ->where('id_analisis', $analisis->id)
            ->get();

        $medicamentos = DB::table('plan_manejo_medicamentos')
            ->where('id_analisis', $analisis->id)
            ->get();

        $procedimientos = DB::table('plan_manejo_procedimientos')
            ->where('id_paciente', $historia->id_paciente)
            ->get();

        $fileName = 'Medicina_' . $profesional->name . '_' . $analisis->created_at . '.pdf';

        $pdf = Pdf::loadView('pdf.medicina', compact('paciente','profesional','diagnosticos','analisis','antecedentes','examenFisico','enfermedades','medicamentos','procedimientos'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
