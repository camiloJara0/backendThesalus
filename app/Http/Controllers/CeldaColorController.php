<?php

namespace App\Http\Controllers;

use App\Models\CeldaColor;
use Illuminate\Http\Request;

class CeldaColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $celdasColors = CeldaColor::all();

        return response()->json([
            'success' => true,
            'data' => $celdasColors
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
        return CeldaColor::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CeldaColor  $celdaColor
     * @return \Illuminate\Http\Response
     */
    public function show(CeldaColor $celdaColor)
    {
        return CeldaColor::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CeldaColor  $celdaColor
     * @return \Illuminate\Http\Response
     */
    public function edit(CeldaColor $celdaColor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CeldaColor  $celdaColor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CeldaColor $celdaColor)
    {
        $celdaColor->update($request->all());
        return $celdaColor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CeldaColor  $celdaColor
     * @return \Illuminate\Http\Response
     */
    public function destroy(CeldaColor $celdaColor)
    {
        $celdaColor->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
