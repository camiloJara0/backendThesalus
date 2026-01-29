<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cita;
use App\Models\Terapia;
use App\Models\Diagnostico;
use App\Models\Diagnostico_relacionado;
use App\Models\Analisis;
use App\Models\Historia_Clinica;

use Illuminate\Http\Request;

class TerapiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terapia = Terapia::get();

        return response()->json([
            'success' => true,
            'data' => $terapia
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
            $ids = [];
            $data = $request->all();

            $historia = Historia_Clinica::where('id_paciente', $request->Terapia['id_paciente'])->first();
            
            // 1️⃣ Guardar Historia Clínica
            if(!$historia){
                $historia = Historia_Clinica::create($data['HistoriaClinica']);
            }
            
            // 2️⃣ Guardar Análisis con id_historia
            $data['Analisis']['id_historia'] = $historia->id;
            
            $analisis = Analisis::create($data['Analisis']);
            $ids['Analisis'] = $analisis->id;
            
            $data['Terapia']['id_analisis'] = $analisis->id;
            if (!empty($data['Terapia'])) {
                $terapia = Terapia::create($data['Terapia']);
                $ids['Terapia'] = $terapia->id;
            }

            $ids['Diagnosticos'] = [];
            foreach ($data['Diagnosticos'] ?? [] as $diagnostico) {
                $nuevo = Diagnostico::create([...$diagnostico, 'id_analisis' => $analisis->id]);
                $ids['Diagnosticos'][] = $nuevo->id;
            }

            $ids['DiagnosticosCIF'] = [];
            foreach ($data['DiagnosticosCIF'] ?? [] as $diagnosticoCIF) {
                $nuevo = Diagnostico_relacionado::create([...$diagnosticoCIF, 'id_analisis' => $analisis->id]);
                $ids['DiagnosticosCIF'][] = $nuevo->id;
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
                'data' => $terapia
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar Terapia', 'message' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Terapia  $terapia
     * @return \Illuminate\Http\Response
     */
    public function show(Terapia $terapia)
    {
        return $terapia;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Terapia  $terapia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Terapia $terapia)
    {
        $terapia = Terapia::find($request->input('id'));

       if($terapia) {
           $terapia->objetivos = $request->objetivos;
           $terapia->fecha = $request->fecha;
           $terapia->hora = $request->hora;
           $terapia->sesion = $request->sesion;
           $terapia->evolucion = $request->evolucion;
           $terapia->save();
    
           return response()->json([
            'success' => true,
            'message' => 'Terapia actualizada exitosamente.',
            'data' => $terapia
           ]);
        };
        
        return response()->json([
            'success' => false,
            'message' => 'Terapia no valida.'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Terapia  $terapia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terapia $terapia)
    {
        //
    }

    public function imprimir($id)
    {
        $terapia = Terapia::where('id_analisis', $id)->first();
        // Paciente con su información de usuario
        $paciente = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->where('pacientes.id', $terapia->id_paciente)
            ->select('pacientes.*', 'informacion_users.*', 'eps.nombre as Eps')
            ->first();

        // Profesional con su información de usuario
        $profesional = DB::table('profesionals')
            ->join('informacion_users', 'profesionals.id_infoUsuario', '=', 'informacion_users.id')
            ->where('profesionals.id', $terapia->id_profesional)
            ->select('profesionals.*', 'informacion_users.*')
            ->first();

        // Diagnósticos que coincidan con el id_analisis
        $diagnosticos = DB::table('diagnosticos')
            ->where('id_analisis', $terapia->id_analisis)
            ->get();

        $diagnosticosCIF = DB::table('diagnostico_relacionados')
            ->where('id_analisis', $terapia->id_analisis)
            ->get();

        $analisis = DB::table('analises')
            ->where('id', $terapia->id_analisis)
            ->first();

        $fileName = 'Terapia_' . $profesional->name . '_' . $terapia->fecha . '.pdf';

        $pdf = Pdf::loadView('pdf.terapia', compact('terapia','paciente','profesional','diagnosticos','diagnosticosCIF','analisis'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
