<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CONSTANCIA DE PRESTACION </title>
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

                        <img src="{{ public_path('logo.png') }}" style="width:60px; height:auto;" />

                    <p><strong>Santa Isabel IPS</strong></p>
                </th>
                <th style="width:50%; text-align:center; font-size:12px;">
                    <p><strong>Proceso:</strong></p>
                    <p><strong>ENTREGAS</strong></p>
                    <p><strong>CONSTANCIA DE PRESTACION</strong></p>
                </th>
                <th style="width:30%; text-align:right; font-size:10px;">
                    <p>Código:  </p>
                    <p>Versión:  </p>
                    <p>Fecha: </p>
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
            <td colspan="2"><strong>DEPARTAMENTO DE PRESTACION:</strong> {{ $paciente->name }}</td>
            <td><strong>CIUDAD DE PRESTACION:</strong></td>
        </tr>
        <tr>
            <td><strong>NOMBRE PACIENTE:</strong> {{ $paciente->No_document }}</td>
            <td><strong>TIPO DE DOCUMENTO:</strong> {{ $paciente->No_document }}</td>
            <td><strong>NUMERO DE DOCUMENTO:</strong> {{ $paciente->No_document }}</td>
        </tr>
        <tr>
            <td><strong>BARRIO DE RESIDENCIA:</strong> {{ $paciente->No_document }}</td>
            <td><strong>DIRECCION DE RESIDENCIA:</strong> {{ $paciente->No_document }}</td>
            <td><strong>LUGAR ADICIONAL DE RESIDENCIA:</strong> {{ $paciente->No_document }}</td>
        </tr>
        <tr>
            <td><strong>NUMERO DE CONTACTO:</strong> {{ $paciente->No_document }}</td>
            <td><strong>TIPO DE VERIFICACION:</strong> Constancia de uso</td>
            <td><strong>TIPO DE VALIDACION:</strong> Presencial</td>
        </tr>
    </table>

    <div class="section">
        <h3>
            DESCRIPCION DE LOS INSUMOS ENTREGADOS
        </h3>
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Descripcion</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left; width: 15%;">Cantidad</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left; width: 15%;">Periodo de prestacion</th>
            </tr>
            @forelse($planes as $equipo)
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $equipo->observacion }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $equipo->cantidad }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;"></td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Sin registrados</td>
            </tr>
            @endforelse
        </table>
    </div>


    <!-- EVOLUCION -->

    <div style="margin-bottom: 20px; font-size:10px;">
        <h3 style="background-color: #f0f0f0; padding: 8px; border: 1px solid #ddd; text-align: center;">OBSERVACIONES
        </h3>
        <div style="text-align: justify; padding: 10px; border: 1px solid #ddd;">
            
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
</body>

</html>