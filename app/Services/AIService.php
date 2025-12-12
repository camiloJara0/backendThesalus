<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function obtenerSugerencia(string $texto)
    {
        $prompt = $this->construirPrompt($texto);

        // Preparar payload
        $payload = [
            'model' => config('services.ai.model'),
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un asistente clínico especializado en redacción médica.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.2,
            'max_tokens' => 80
        ];

        // Llamar a la IA
        $response = Http::withToken(config('services.ai.api_key'))
            ->post(config('services.ai.endpoint'), $payload);

        if ($response->failed()) {
            \Log::error($response->body());
            return ''; // fallback
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? '';

    }


    private function construirPrompt(string $textoParcial)
    {
        return <<<PROMPT
        Tarea: Sugerir cómo continuar una nota clínica, de forma profesional, neutra y sin datos personales.

        Texto actual:
        "$textoParcial"

        Reglas:
        - Continuar máximo con dos oraciones.
        - Sin nombres propios, documentos, identificadores ni datos sensibles.
        - Mantener terminología médica estandarizada.
        - No repetir lo ya escrito.
        PROMPT;
    }
}
