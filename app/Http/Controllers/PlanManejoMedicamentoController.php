<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Clegginabox\PDFMerger\PDFMerger;
use App\Models\Historia_Clinica;
use App\Models\Analisis;
use App\Models\Plan_manejo_medicamento;
use App\Models\Plan_manejo_equipo;
use App\Models\Plan_manejo_insumo;
use App\Models\Historial_insumoprestado;
use Illuminate\Http\Request;
use App\Models\Movimiento;
use App\Models\Insumo;

class PlanManejoMedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicamentos = Plan_manejo_medicamento::get();
        return response()->json([
            'success' => true,
            'data' => $medicamentos
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
        
        $data = $request->all();
        $planes = [];
        $pdfCOMODATO = false;
        $equipos = [];
        $pdfMEDICAMENTO = false;
        $medicamentos = [];

        if (!empty($data['Plan_manejo_medicamentos'])) {
            $planes['Plan_manejo_medicamentos'] = [];


            foreach ($data['Plan_manejo_medicamentos'] as $item) {
                // Crear el registro del plan de manejo de medicamento
                $insumo = Insumo::find($item['id_insumo']);

                // if($insumo->categoria == 'Medicamento'){
                //     $nuevo = Plan_manejo_medicamento::create($item);
                //     $planes['Plan_manejo_medicamentos'][] = $nuevo;
                // } else if($insumo->categoria == 'Equipos médicos'){
                //     $nuevo = Plan_manejo_equipo::create([
                //         'descripcion' => $item->medicamento,
                //         'uso' => $item->cantidad,
                //         'observacion' => $item->observacion,
                //     ]);
                //     $planes['Plan_manejo_medicamentos'][] = $nuevo;
                // } else {
                //     $nuevo = Plan_manejo_insumo::create([
                //         'nombre' => $item->medicamento,
                //         'cantidad' => $item->cantidad,
                //         'observacion' => $item->observacion,
                //     ]);
                //     $planes['Plan_manejo_medicamentos'][] = $nuevo;
                // }

                // Definir cantidadMovimiento
                $cantidadMovimiento = $item['cantidad'] ?? 0;


                    if ($insumo) {
                        $movimiento = Movimiento::create([
                            'cantidadMovimiento' => $cantidadMovimiento,
                            'fechaMovimiento'    => now(),
                            'tipoMovimiento'     => $insumo->es_prestable ? 'Prestado' : 'Egreso',
                            'id_medico'          => $item['id_medico'] ?? null,
                            'id_insumo'          => $item['id_insumo'],
                            'id_paciente'        => $item['id_paciente'],
                        ]);

                        // Actualizar stock
                        $insumo->stock -= $cantidadMovimiento;
                        $insumo->save();
                    }

                    if($insumo->es_prestable){
                        $pdfCOMODATO = true;
                        $equipos[] = $insumo->toArray() + ['fecha' => now()] + $nuevo->toArray();
                        Historial_insumoprestado::create([
                            'id_insumo' => $insumo->id,
                            'id_movimiento' => $movimiento->id,
                            'fecha_desde' => $item['fecha_desde'],
                            'fecha_hasta' => $item['fecha_hasta'],
                            'observacion' => $item['observacion'],
                            'estado' => 'Prestado',
                        ]);
                    } else if($insumo->categoria == 'Medicamento'){
                        $pdfMEDICAMENTO = true;
                        $medicamentos[] = $item;
                    }

            }
        }

        // Paciente con su información de usuario
        $paciente = DB::table('pacientes')
            ->join('informacion_users', 'pacientes.id_infoUsuario', '=', 'informacion_users.id')
            ->join('eps', 'pacientes.id_eps', '=', 'eps.id')
            ->where('pacientes.id', $data['id_paciente'])
            ->select('pacientes.*', 'informacion_users.*', 'eps.nombre as Eps')
            ->first();

        // Profesional con su información de usuario
        $profesional = DB::table('profesionals')
            ->join('informacion_users', 'profesionals.id_infoUsuario', '=', 'informacion_users.id')
            ->where('profesionals.id', $data['id_medico'] ?? 0)
            ->select('profesionals.*', 'informacion_users.*')
            ->first();

        $fileName = 'ActaEntrega_' . $paciente->name . '_' . '.pdf';

        if($pdfMEDICAMENTO){
            $pdfActa = Pdf::loadView('pdf.actaEntregaMedicamentos', [
                'paciente'    => $paciente,
                'profesional' => $profesional,
                'planes'      => $medicamentos
            ])->output();
    
            // Inicializar merger
            $merger = new PDFMerger;
    
            // Agregar ActaEntrega
            $pathActa = storage_path('app/temp_acta.pdf');
            file_put_contents($pathActa, $pdfActa);
            $merger->addPDF($pathActa, 'all');
        }

        // Si hay equipos médicos, generar Comodato
        if ($pdfCOMODATO) {
            $pdfComodato = Pdf::loadView('pdf.comodato', [
                'paciente'    => $paciente,
                'profesional' => $profesional,
                'equipos'     => $equipos
            ])->output();

            $pathComodato = storage_path('app/temp_comodato.pdf');
            file_put_contents($pathComodato, $pdfComodato);
            $merger->addPDF($pathComodato, 'all');
        }

        // Constancia de prestación siempre
        $pdfConstancia = Pdf::loadView('pdf.constanciaPrestacion', [
            'paciente'    => $paciente,
            'profesional' => $profesional,
            'planes'      => $equipos
        ])->output();

        $pathConstancia = storage_path('app/temp_constancia.pdf');
        file_put_contents($pathConstancia, $pdfConstancia);
        $merger->addPDF($pathConstancia, 'all');

        // Fusionar
        $finalPath = storage_path('app/' . $fileName);
        $merger->merge('file', $finalPath);


        return response()->download($finalPath, $fileName, [
            'Content-Type' => 'application/pdf',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Expose-Headers' => 'Content-Disposition'
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan_manejo_medicamento  $plan_manejo_medicamento
     * @return \Illuminate\Http\Response
     */
    public function show(Plan_manejo_medicamento $plan_manejo_medicamento)
    {
        return $plan_manejo_medicamento;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan_manejo_medicamento  $plan_manejo_medicamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan_manejo_medicamento $plan_manejo_medicamento)
    {
        $plan_manejo_medicamento = Plan_manejo_medicamento::where('id', $request->id)->first();

        if($plan_manejo_medicamento){
            $plan_manejo_medicamento->medicamento = $request->medicamento;
            $plan_manejo_medicamento->dosis = $request->dosis;
            $plan_manejo_medicamento->cantidad = $request->cantidad;
            $plan_manejo_medicamento->save();
        }

        // Respuesta
        return response()->json([
            'success' => true,
            'message' => 'Plan de manejo de medicamento actualizado exitosamente.',
            'data' => $plan_manejo_medicamento
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan_manejo_medicamento  $plan_manejo_medicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan_manejo_medicamento $plan_manejo_medicamento)
    {
        //
    }

    public function imprimirFormulaMedica($id)
    {
        $analisis = Analisis::with('servicio')->find($id);

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

        $medicamentos = DB::table('plan_manejo_medicamentos')
            ->leftJoin('insumos', 'plan_manejo_medicamentos.medicamento', '=', 'insumos.nombre')
            ->where('plan_manejo_medicamentos.id_analisis', $analisis->id)
            ->select('plan_manejo_medicamentos.*', 'insumos.*')
            ->get();


        $fileName = 'Formula_' . $profesional->name . '_' . $analisis->created_at . '.pdf';

        $pdf = Pdf::loadView('pdf.formulaMedica', compact('paciente','profesional','analisis','medicamentos',));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
