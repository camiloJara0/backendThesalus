<?php

namespace App\Http\Controllers;

use App\Models\Secciones;
use Illuminate\Http\Request;

class SeccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $secciones = Secciones::get();

        return response()->json([
            'success' => true,
            'data' => $secciones
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function show(Profesion $profesion)
    {

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


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profesion  $profesion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profesion $profesion)
    {

    }
}
