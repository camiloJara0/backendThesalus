<?php

namespace App\Http\Controllers;

use App\Models\Descripcion_nota;
use Illuminate\Http\Request;

class DescripcionNotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $descripcion_nota = Descripcion_nota::get();

        return response()->json([
            'success' => true,
            'data' => $descripcion_nota
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
     * @param  \App\Models\DescripcionNota  $descripcion_nota
     * @return \Illuminate\Http\Response
     */
    public function show(DescripcionNota $descripcion_nota)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DescripcionNota  $descripcion_nota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DescripcionNota $descripcion_nota)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DescripcionNota  $descripcion_nota
     * @return \Illuminate\Http\Response
     */
    public function destroy(DescripcionNota $descripcion_nota)
    {

    }
}
