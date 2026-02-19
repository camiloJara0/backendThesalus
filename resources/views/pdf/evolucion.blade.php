<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SERVICIO DE EVOLUCIÓN</title>
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

    @page {
        margin: 140px 40px 60px 40px;
    }

    header {
        position: fixed;
        top: -120px;
        left: 0;
        right: 0;
        height: 80px;
    }

    .pagenum:before {
        content: counter(page);
    }
    </style>
</head>

<body>
    <!-- ENCABEZADO -->
    <header>
        <table style="width:100%; border-bottom:2px solid #000; font-size:12px;">
            <tr>
                <th style="width:20%; text-align:center;">
                    <img src="{{ public_path('logo.png') }}" style="width:60px;" />
                    <p><strong>Santa Isabel IPS</strong></p>
                </th>
                <th style="width:50%; text-align:center; font-size:12px;">
                    <p><strong>Proceso:</strong> Programa de Atención Domiciliaria</p>
                    <p><strong>Registro {{ $analisis->servicio->name }}</strong></p>
                    <p><strong>Hoja de evolución</strong></p>
                </th>
                <th style="width:30%; text-align:right; font-size:10px;">
                    <p>Código:  </p>
                    <p>Versión:  </p>
                    <p>Fecha: {{ $analisis->created_at->format('Y-m-d') }}</p>
                    <p>Página: <span class="pagenum"></span></p>
                </th>
            </tr>
        </table>
        <div style="height:30px;"></div>
    </header>

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
            EVOLUCIÓN
        </h3>
        <div style="margin-bottom: 20px; font-size:10px;">
            <h3 style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ddd; text-align: center;">Motivo de
                consulta</h3>
            <div style="text-align: justify; padding: 10px; border: 1px solid #ddd;">
                {{ $analisis->motivo }}
            </div>
        </div>
    </div>


    <!-- EVOLUCION -->

    <div style="margin-bottom: 20px; font-size:10px;">
        <h3 style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ddd; text-align: center;">RECOMENDACIONES
        </h3>
        <div style="text-align: justify; padding: 10px; border: 1px solid #ddd;">
            {{ $analisis->analisis }}
        </div>
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

    @if($medicamentos->isNotEmpty())
        <h3 style="page-break-before: always;">DATOS DEL PACIENTE</h3>
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
        <div style="margin-bottom: 20px;">
            <h3
                style="font-size: 13px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; text-transform: uppercase;">
                PLAN DE MANEJO
            </h3>
            <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
                <tr style="background-color: #f0f0f0;">
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Nombre del Medicamento</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Dosis</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Cantidad</th>
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
    @endif
</body>

</html>