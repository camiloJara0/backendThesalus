<?php

namespace App\Http\Controllers;

use App\Models\Vadecum;
use Illuminate\Http\Request;

class VadecumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vadecums = Vadecum::get();

        return response()->json([
            'success' => true,
            'data' => $vadecums
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $codigos = [];

        foreach ($data['vadecums'] ?? [] as $cum) {
            $nuevo = Vadecum::create([...$cum]);
            $codigos['cum'][] = $nuevo;
        }

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Vademecums creado exitosamente.',
            'data' => $codigos
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vadecum  $vadecum
     * @return \Illuminate\Http\Response
     */
    public function show(Vadecum $vadecum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vadecum  $vadecum
     * @return \Illuminate\Http\Response
     */
    public function edit(Vadecum $vadecum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vadecum  $vadecum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vadecum $vadecum)
    {
        $vadecum->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Vademecum creado exitosamente.',
            'data' => $vadecum
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vadecum  $vadecum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vadecum $vadecum)
    {
        $vadecum->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vademecum eliminado exitosamente.',
        ], 200);
    }
}
