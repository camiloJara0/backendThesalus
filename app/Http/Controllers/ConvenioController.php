<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConvenioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $convenios = Convenio::where('estado', 1)->with('pacientes')->get();

        return response()->json([
            'success' => true,
            'data' => $convenios
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
        // Reglas recomendadas de validación
        $logoPath = null;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $file = $request->file('logo');
                // Nombre seguro y único
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                // Ruta dentro del disco public
            $folder = 'convenios';
                // Si no usamos intervention simplemente guardamos
            $path = $file->storeAs($folder, $filename, 'public'); // devuelve 'convenios/xxx.jpg'
            $logoPath = $path;
        }

        $convenio = Convenio::create([
            'logo' => $logoPath
        ] + $request->all());

            // 2️⃣ Asociar convenios si vienen en el request
            if (!empty($request->pacientes_ids)) {
                foreach ($request->pacientes_ids as $pacienteId) {
                    $paciente = Paciente::find($pacienteId);

                    if ($paciente) {
                        DB::table('paciente_has_convenios')->insert([
                            'id_paciente' => $paciente->id,
                            'id_convenio' => $convenio->id
                        ]);
                    }
                }
            }


        return response()->json([
            'success' => true,
            'data' => $convenio
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Convenio  $convenio
     * @return \Illuminate\Http\Response
     */
    public function show(Convenio $convenio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Convenio  $convenio
     * @return \Illuminate\Http\Response
     */
    public function edit(Convenio $convenio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Convenio  $convenio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Convenio $convenio)
    {
        // Si ya existe un logo, lo borramos
        if ($request->hasFile('logo') && !empty($convenio->logo)) {
            Storage::disk('public')->delete($convenio->logo);
        }

        $logoPath = $convenio->logo; // mantener el anterior si no se sube uno nuevo

        // Si viene un archivo válido
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $file = $request->file('logo');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $folder = 'convenios';
            $path = $file->storeAs($folder, $filename, 'public');
            $logoPath = $path;
        }

        $convenio->nombre = $request->nombre;
        $convenio->logo = $logoPath;
        $convenio->save();

        // 2️⃣ Obtener IDs de permisos desde nombres
        $pacientesconConvenio = [];
        if (!empty($request->pacientes_ids) && is_array($request->pacientes_ids)) {
            $pacientesconConvenio = Paciente::whereIn('id', $request->pacientes_ids)->pluck('id')->toArray();
        }

        // 3️⃣ Sincronizar permisos (agrega nuevos y elimina los que no están)
        $convenio->pacientes()->sync($pacientesconConvenio);

        return response()->json([
            'success' => true,
            'data' => $convenio
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Convenio  $convenio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convenio $convenio)
    {
        if (!empty($convenio->logo)) {
            Storage::disk('public')->delete($convenio->logo);
        }
        $convenio->estado = 0;
        $convenio->save();
        return response()->json([
            'success' => true
        ]);
    }
}
