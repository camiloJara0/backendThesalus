<?php

namespace App\Http\Controllers;

use App\Models\Eps;
use Illuminate\Http\Request;

class EpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eps = Eps::where('estado', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $eps
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
        $eps = new Eps();
        $eps -> nombre = $request->nombre;
        $eps -> codigo = $request->codigo;
        $eps -> nit = $request->nit;
        $eps -> save();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'EPS registrada exitosamente.',
            'data' => $eps
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eps  $eps
     * @return \Illuminate\Http\Response
     */
    public function show(Eps $eps)
    {
        return $eps;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eps  $eps
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eps $eps)
    {
        $eps = Eps::where('id', $request->id)->first();
        if($eps){
            $eps -> nombre = $request->nombre;
            $eps -> codigo = $request->codigo;
            $eps -> nit = $request->nit;
            $eps -> save();
            // Retornar respuesta
            return response()->json([
                'success' => true,
                'message' => 'EPS actualizada exitosamente.',
                'data' => $eps
            ], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eps  $eps
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Eps $eps)
    {
        $eps = Eps::where('id', $request->id)->first();

        if($eps){
            $eps->estado = 0;
            $eps -> save();
        }

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'EPS eliminada exitosamente.',
            'data' => $eps
        ], 201);
    }
}
