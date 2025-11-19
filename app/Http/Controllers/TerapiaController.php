<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Cita;
use App\Models\Terapia;

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

            if (!empty($data['Terapia'])) {
                $terapia = Terapia::create($data['Terapia']);
                $ids['Terapia'] = $terapia->id;
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
       //
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
}
