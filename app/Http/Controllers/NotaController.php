<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;

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
        $nota = Nota::where('id', $request->id)->first();
        if($nota){
            $nota->id_paciente = $request->id_paciente;
            $nota->id_procedimiento = $request->id_procedimiento;
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
                'message' => 'Nota actualizada exitosamente.',
                'data' => $nota
            ], 201);
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
