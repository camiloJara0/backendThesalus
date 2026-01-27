@php
    // Decodificar el JSON a un array asociativo
    $signos = json_decode($examenFisico->signosVitales, true);
@endphp


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Medicina</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        color: #333;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #ddd;
        padding: 5px;
        font-size: 10px;
    }

    h3 {
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 10px;
        border-bottom: 1px solid #000;
        padding-bottom: 5px;
    }
    </style>
</head>

<body>
    <!-- ENCABEZADO -->
    <table style="border-bottom:2px solid #000; margin-bottom:15px; font-size:12px;">
        <tr>
            <th style="width:20%; text-align:center;">
                <img src="{{ public_path('logo.png') }}" style="width:60px; height:auto;" />
                <p><strong>Santa Isabel IPS</strong></p>
            </th>
            <th style="width:50%; text-align:left;">
                <p><strong>Proceso:</strong> Programa de Atención Domiciliaria</p>
                <p><strong>Registro {{ $analisis->nombreServicio }}</strong></p>
                <p><strong>Historia Clinica </br>
                        Medicina general domiciliaria</strong></p>
            </th>
            <th style="width:30%; text-align:right; font-size:10px;">
                <p>Código:</p>
                <p>Versión:</p>
                <p>Fecha: {{ $analisis->created_at->format('Y-m-d') ?? \Carbon\Carbon::now()->format('Y-m-d') }}</p>
                <p>Página: 1 de 1</p>
            </th>
        </tr>
    </table>

    <!-- DATOS DEL PACIENTE -->
    <h3>DATOS DEL PACIENTE</h3>
    <table>
        <tr>
            <td><strong>Nombre completo:</strong> {{ $paciente->name }}</td>
            <td></td>
        </tr>
        <tr>
            <td>
                <strong>No. documento:</strong> {{ $paciente->No_document }}<br />
                <strong>Tipo de documento:</strong> {{ $paciente->type_doc }}
            </td>
            <td>
                <strong>Edad:</strong> {{ \Carbon\Carbon::parse($paciente->nacimiento)->age }}<br />
                <strong>Sexo:</strong> {{ $paciente->sexo }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>EPS:</strong> {{ $paciente->Eps }} | <strong>Zona:</strong> {{ $paciente->zona ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <!-- DIAGNÓSTICOS -->
    <div style="margin-bottom: 20px;">
        <h3
            style="font-size: 13px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px;">
            DIAGNÓSTICOS
        </h3>
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Diagnóstico</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left; width: 15%;">CIE-10</th>
            </tr>
            @forelse($diagnosticos as $diag)
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $diag->descripcion }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $diag->codigo }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Sin diagnósticos registrados</td>
            </tr>
            @endforelse
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3
            style="font-size: 13px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px;">
            Evolucion
        </h3>
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Motivo de consulta</th>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $analisis->motivo }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Enfermedad Actual</th>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $enfermedades->valor }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Antecedentes</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Tipo</th>
            </tr>
            @forelse($antecedentes as $antecedente)
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $antecedente->descripcion }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $antecedente->tipo }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Sin antecedentes registrados</td>
            </tr>
            @endforelse
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3
            style="font-size: 13px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px;">
            Examen Fisico
        </h3>
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">TA</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">FC</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">FR</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">T</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">SAT o2</th>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $signos['ta'] ?? 'N/A' }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $signos['fc'] ?? 'N/A' }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $signos['fr'] ?? 'N/A' }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $signos['t'] ?? 'N/A' }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $signos['SATo2'] ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- EVOLUCION -->
    <div style="margin-bottom: 20px;">
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Analisis / Tratamiento</th>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $analisis->analisis }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3
            style="font-size: 13px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; text-transform: uppercase;">
            Plan de manejo
        </h3>
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Nombre del Medicamento</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Dosis</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Cantidad de dias</th>
            </tr>
            @forelse($medicamentos as $medicamento)
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $medicamento->medicamento }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $medicamento->dosis }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $medicamento->cantidad }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Sin medicamentos registrados</td>
            </tr>
            @endforelse
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3
            style="font-size: 13px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; text-transform: uppercase;">
            Procedimientos
        </h3>
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Decripcion</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">CUPS</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Dias asignados</th>
            </tr>
            @forelse($procedimientos as $procedimiento)
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $procedimiento->procedimiento }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $procedimiento->codigo }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $procedimiento->dias_asignados }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Sin procedimientos registrados</td>
            </tr>
            @endforelse
        </table>
    </div>

    <!-- FIRMA Y SELLO -->
    <table style="margin-top:40px;">
        <tr>
            <td style="text-align:center; border-top:1px solid #000;">
                <p><strong>{{ $profesional->name }}</strong></p>
                <p>{{ $profesional->No_document }}</p>
            </td>
            <td style="text-align:center; border-top:1px solid #000;">
                @if($profesional->sello)
                <img src="{{ public_path('storage/'.$profesional->sello) }}"
                    style="width:100px; height:100px; object-fit:contain;" />
                @else
                <p>Firma y Sello</p>
                @endif
            </td>
        </tr>
    </table>
</body>

</html>