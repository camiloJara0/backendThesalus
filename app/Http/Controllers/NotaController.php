<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Descripcion_nota;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Diagnostico;
use App\Models\Paciente;
use App\Models\Profesional;
use App\Models\InformacionUser;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nota = Nota::get();

        return response()->json([
            'success' => true,
            'data' => $nota
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
        // Crear la nueva nota
        $nota = new Nota();
        $nota->id_paciente = $request->id_paciente;
        $nota->direccion = $request->direccion;
        $nota->fecha_nota = $request->fecha_nota;
        $nota->hora_nota = $request->hora_nota;
        $nota->tipoAnalisis = $request->tipoAnalisis;
        $nota->save();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Nota creada exitosamente.',
            'data' => $nota
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Nota  $nota
     * @return \Illuminate\Http\Response
     */
    public function show(Nota $nota)
    {
        return $nota;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nota  $nota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nota $nota)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $ids = [];

            // Actualizar nota
            $nota = Nota::where('id', $request->Nota['id'])->first();

            $nota->id_paciente = $request->Nota['id_paciente'];
            $nota->direccion = $request->Nota['direccion'];
            $nota->fecha_nota = $request->Nota['fecha_nota'];
            $nota->hora_nota = $request->Nota['hora_nota'];
            $nota->tipoAnalisis = $request->Nota['tipoAnalisis'];
            $nota->save();

            $ids['Descripcion'] = [];

            foreach ($data['Descripcion'] ?? [] as $descripcion) {
                $nuevo = Descripcion_nota::updateOrCreate(
                    ['id' => $descripcion['id'] ?? null], // condición de búsqueda
                    [
                        'hora'        => $descripcion['hora'],
                        'descripcion' => $descripcion['descripcion'],
                        'tipo'        => $descripcion['tipo'],
                        'id_nota'     => $nota->id,
                    ]
                );

                $ids['Descripcion'][] = $nuevo->id;
            }


            DB::commit();

            return response()->json([
                'success' => true, 
                'ids' => $ids,
                'data' => $nota,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar Notas Medicas', 'message' => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nota  $nota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nota $nota)
    {
        //
    }

    public function imprimir($id)
    {
        $nota = Nota::where('id_analisis', $id)->first();

        $analisis =DB::table('analises')
            ->join('servicio', 'analises.id_servicio', '=', 'servicio.id')
            ->select(
                'analises.*',
                'servicio.plantilla as servicio',
                'servicio.name as nombreServicio'
            )
            ->where('analises.id', $nota->id_analisis)
            ->first();

        // Paciente con su información de usuario
        $paciente = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->where('pacientes.id', $nota->id_paciente)
            ->select(
                'pacientes.*',
                'informacion_users.*',
                'eps.nombre as Eps' // aquí traes el nombre de la EPS con alias
            )
            ->first();

        // Profesional con su información de usuario
        $profesional = DB::table('profesionals')
            ->join('informacion_users', 'profesionals.id_infoUsuario', '=', 'informacion_users.id')
            ->where('profesionals.id', $analisis->id_medico)
            ->select('profesionals.*', 'informacion_users.*')
            ->first();

        // Diagnósticos que coincidan con el id_analisis
        $diagnosticos = DB::table('diagnosticos')
            ->where('id_analisis', $nota->id_analisis)
            ->get();

        $descripcion = DB::table('descripcion_nota')
            ->where('id_nota', $nota->id)
            ->get();

        $fileName = 'Nota_' . $profesional->name . '_' . $nota->fecha_nota . '.pdf';

        $pdf = Pdf::loadView('pdf.nota', compact('nota','paciente','profesional','diagnosticos','descripcion','analisis'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');


        // return $pdf->stream('nota.pdf'); // mostrar en navegador
        // return $pdf->download('nota.pdf'); // descargar directamente
    }

}
