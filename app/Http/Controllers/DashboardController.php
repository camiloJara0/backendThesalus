<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Paciente;
use App\Models\Cita;

class DashboardController extends Controller
{
    private function pacientesTotales()
    {
        $inicioMesActual = Carbon::now()->startOfMonth();
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();

        $totalActual = Paciente::count();

        $pacientesMesActual = Paciente::where('created_at', '>=', $inicioMesActual)->count();

        $pacientesMesAnterior = Paciente::whereBetween('created_at', [
            $inicioMesAnterior,
            $finMesAnterior
        ])->count();

        $variacion = $pacientesMesAnterior > 0
            ? round((($pacientesMesActual - $pacientesMesAnterior) / $pacientesMesAnterior) * 100, 2)
            : 100;

        return [
            'total' => $totalActual,
            'variacion' => $variacion
        ];
    }

    private function consultasRealizadas()
    {
        $inicioMesActual = Carbon::now()->startOfMonth();
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();

        $realizadasMesActual = Cita::where('estado', 'Realizada')
            ->where('updated_at', '>=', $inicioMesActual)
            ->count();

        $realizadasMesAnterior = Cita::where('estado', 'Realizada')
            ->whereBetween('updated_at', [
                $inicioMesAnterior,
                $finMesAnterior
            ])
            ->count();

        $variacion = $realizadasMesAnterior > 0
            ? round((($realizadasMesActual - $realizadasMesAnterior) / $realizadasMesAnterior) * 100, 2)
            : 100;

        return [
            'total' => $realizadasMesActual,
            'variacion' => $variacion
        ];
    }

    private function citasProgramadas()
    {
        $inicioMesActual = Carbon::now()->startOfMonth();
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();

        $programadasMesActual = Cita::where('estado', 'Inactiva')
            ->where('created_at', '>=', $inicioMesActual)
            ->count();

        $programadasMesAnterior = Cita::where('estado', 'Inactiva')
            ->whereBetween('created_at', [
                $inicioMesAnterior,
                $finMesAnterior
            ])
            ->count();

        $variacion = $programadasMesAnterior > 0
            ? round((($programadasMesActual - $programadasMesAnterior) / $programadasMesAnterior) * 100, 2)
            : 100;

        return [
            'total' => $programadasMesActual,
            'variacion' => $variacion
        ];
    }

    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'pacientes' => $this->pacientesTotales(),
                'consultas' => $this->consultasRealizadas(),
                'citas_programadas' => $this->citasProgramadas(),
            ],
        ]);
    }

}
