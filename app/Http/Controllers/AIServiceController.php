<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;

class AIServiceController extends Controller
{
    protected $ai;

    public function __construct(AIService $ai)
    {
        $this->ai = $ai;
    }

    public function sugerencia(Request $request)
    {
        $texto = $request->input('texto');

        if (!$texto) {
            return response()->json(['sugerencia' => '']);
        }

        $sugerencia = $this->ai->obtenerSugerencia($texto);

        return response()->json([
            'success' => true,
            'sugerencia' => $sugerencia
        ]);
    }
}
