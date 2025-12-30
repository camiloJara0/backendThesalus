<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Descripcion_nota;

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
        $nota->id_procedimiento = null;
        $nota->id_profesional = $request->id_profesional;
        $nota->direccion = $request->direccion;
        $nota->fecha_nota = $request->fecha_nota;
        $nota->hora_nota = $request->hora_nota;
        $nota->nota = $request->nota;
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
            $nota->id_profesional = $request->Nota['id_profesional'];
            $nota->direccion = $request->Nota['direccion'];
            $nota->fecha_nota = $request->Nota['fecha_nota'];
            $nota->hora_nota = $request->Nota['hora_nota'];
            $nota->nota = $request->Nota['nota'] ?? 'nota';
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
}
