<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CONTRATO DE COMODATO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
        }

        h3 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a3a5c;
            border-bottom: 2px solid #1a3a5c;
            padding-bottom: 4px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 5px;
        }

        th {
            background-color: #1a3a5c;
            color: #fff;
            padding: 6px;
            font-size: 10px;
            text-align: left;
        }

        td {
            border: 1px solid #dcdcdc;
            padding: 6px;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #e8eff7;
        }

        .section {
            margin-bottom: 18px;
            padding: 10px;
            background: #f9fbfe;
            border: 1px solid #e8eff7;
            border-radius: 4px;
        }

        .title-main {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1a3a5c;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
        }

        header {
            position: fixed;
            top: -120px;
            left: 0;
            right: 0;
            height: 80px;
            border-bottom: 3px solid #1a3a5c;
            text-align: center;
            padding-bottom: 10px;
        }

        .header-text {
            font-size: 12px;
            font-weight: bold;
            color: #1a3a5c;
        }

        @page {
            margin: 90px 40px 80px 40px;
        }

        .firma {
            text-align: center;
            margin-top: 40px;
        }

        .firma p {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 10px;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 2px solid #1a3a5c;
            font-size: 10px;
            color: #1a3a5c;
            text-align: center;
            line-height: 20px;
        }

        .pagenum:before {
            content: counter(page);
        }

        .pagecount:before {
            content: counter(pages);
        }
    </style>
</head>

<body>
    <!-- ENCABEZADO -->
    <header>
        <div class="header-text">
            CONTRATO DE COMODATO - EQUIPO DE OXÍGENO
        </div>
    </header>
    <!-- DATOS DEL PACIENTE -->
    <div class="title-main">Contrato de Comodato</div>
    <div class="subtitle">Préstamo de Uso – Equipos de Oxígeno Médico</div>
    <p class=title-main>No. ________________</p>

    <!-- DIAGNÓSTICOS -->
    <div class="section">
        <h3>
            COMODANTE ( Empresa )
        </h3>
        <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
            <tr>
                <td>Razon Social</td>
                <td></td>
            </tr>
            <tr>
                <td>NIT / CC</td>
                <td></td>
            </tr>
            <tr>
                <td>Direccion</td>
                <td></td>
            </tr>
            <tr>
                <td>Telefono</td>
                <td></td>
            </tr>
            <tr>
                <td>Representante Legal</td>
                <td></td>
            </tr>
            <tr>
                <td>Ciudad</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>
            COMODATARIO ( Paciente/ Usuario)
        </h3>
        <table>
            <tr>
                <td>Nombre completo</td>
                <td>{{ $paciente->name }}</td>
            </tr>
            <tr>
                <td>No. documento</td>
                <td>{{ $paciente->No_document }}</td>
            </tr>
            <tr>
                <td>Tipo documento</td>
                <td>{{ $paciente->type_doc }}</td>
            </tr>
            <tr>
                <td>Direccion residencia</td>
                <td>{{ $paciente->direccion }}</td>
            </tr>
            <tr>
                <td>Telefono / celular</td>
                <td>{{ $paciente->celular }}</td>
            </tr>
            <tr>
                <td>Correo electronico</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td>Medico tratante</td>
                <td>{{ $profesional->name }}</td>
            </tr>
            <tr>
                <td>EPS / Aseguradora</td>
                <td>{{ $paciente->Eps }}</td>
            </tr>
            <tr>
                <td>Ciudad</td>
                <td>{{ $paciente->municipio }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>
            DESCRIPCION DEL EQUIPO ENTREGADO
        </h3>
        <table>
            <tr>
                <th>Tipo de equipo</th>
                <th>Equipo</th>
                <th>Lote / Referencia</th>
                <th>Fecha de Entrega</th>
            </tr>
            @forelse($equipos as $equipo)
            <tr>
                <td>{{ $equipo['categoria'] }}</td>
                <td>{{ $equipo['nombre'] }}</td>
                <td>{{ $equipo['lote'] }}</td>
                <td>{{ $equipo['fecha_desde'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2">Sin registrados</td>
            </tr>
            @endforelse
        </table>
    </div>

    <h3>Clausulas y condiciones</h3>

    <h3>CLÁUSULA PRIMERA – OBJETO DEL CONTRATO</h3>
    <p>
        Mediante el presente contrato de comodato, el COMODANTE entrega a título de préstamo
        de uso, de forma gratuita, el equipo de oxígeno descrito en el numeral II al
        COMODATARIO, para uso exclusivo en su tratamiento médico personal. El
        COMODATARIO se obliga a restituir en las mismas condiciones en que fue recibido, una
        vez venza el plazo pactado o cuando el COMODANTE lo requiera, este contrato no
        transfiere la propiedad al COMODATARIO en ningún momento.
    </p>

    <h3>CLÁUSULA SEGUNDA – PLAZO DE ENTREGA Y DEVOLUCIÓN</h3>
    <p>
        El presente comodato tendrá una duración según los términos establecidos entre el
        COMODANTE y las aseguradoras del riesgo en salud (EAPB o EPS), contados a
        partir de la fecha de suscripción de este documento. Vencido dicho plazo, el
        COMODATARIO deberá devolver el equipo al COMODANTE o notificar oportunamente
        la necesidad de renovación. En caso de requerirse renovación, las partes podrán suscribir
        un otrosí al presente contrato. El incumplimiento en la devolución dentro del plazo
        acordado dará lugar a la aplicación de la cláusula penal establecida en el presente
        documento.
    </p>
    
    <h3>CLÁUSULA TERCERA – OBLIGACIONES DEL COMODATARIO</h3>
    <p>
        El COMODATARIO se compromete a:
        1. Utilizar el equipo únicamente para el tratamiento médico personal prescrito, sin
        destinarlo a actividades distintas a las aquí acordadas.
        2. Custodiar y conservar el equipo en buen estado, evitando su deterioro, daño, pérdida
        o destrucción total o parcial.
        3. Abstenerse de prestar, arrendar, ceder o permitir el uso del equipo a terceras
        personas.
        4. Informar al COMODANTE, dentro de las 24 horas siguientes, sobre cualquier falla,
        daño, anomalía o deterioro que presente el equipo, a los canales de contacto
        establecidos.
        5. No realizar modificaciones, reparaciones, ni intervenciones técnicas al equipo sin
        previa autorización escrita del COMODANTE.
        6. Permitir el acceso del COMODANTE o personal autorizado para realizar
        mantenimiento preventivo o correctivo del equipo, previa coordinación y con previo
        aviso.
        7. Restituir el equipo en el plazo pactado, en perfectas condiciones de funcionamiento y
        con todos sus accesorios, salvo el desgaste normal por uso adecuado del equipo.
    </p>

    <h3>CLÁUSULA CUARTA – OBLIGACIONES DEL COMODANTE</h3>
    <p>
        El COMODANTE se compromete a: (i) Entregar el equipo en buen estado de
        funcionamiento; (ii) Proporcionar al COMODATARIO las instrucciones de uso y
        mantenimiento básico; (iii) Atender oportunamente los reportes de fallas o daños del
        equipo; (iv) Realizar el mantenimiento técnico necesario cuando sea requerido, siempre
        que el daño no sea imputable al COMODATARIO y sustituir el equipo en un plazo
        razonable en caso de falla que afecte el tratamiento.
    </p>

    <h3>CLÁUSULA QUINTA – CLÁUSULA PENAL POR MAL USO O INCUMPLIMIENTO</h3>
    <p>
        En caso de daño, pérdida, hurto o robo del equipo (cuando no sea reportado
        oportunamente), entrega extemporánea o mal uso imputable al COMODATARIO, este
        deberá pagar al COMODANTE, a título de pena, una suma equivalente al ochenta por 
        ciento (80%) del valor comercial del equipo al momento del incumplimiento, conforme a
        la lista de precios vigente del COMODANTE o al valor de reposición del mismo.
        El pago de la pena no exonera al COMODATARIO de la obligación de restituir el equipo
        en caso de ser posible, ni de indemnizar los perjuicios adicionales que se llegaren a
        demostrar. En consecuencia, el COMODANTE podrá exigir tanto el pago de la cláusula
        penal como la indemnización de perjuicios de manera simultánea, de conformidad con lo
        dispuesto en los artículos 1593 y 1600 del Código Civil Colombiano.
    </p>

    <h3>CLÁUSULA SEXTA – RESPONSABILIDAD POR PÉRDIDA O DAÑO TOTAL</h3>
    <p>
        Si el equipo sufre pérdida total, destrucción o daño irreparable por causas imputables al
        COMODATARIO, éste estará obligado a pagar el valor comercial de reposición del
        equipo, sin perjuicio de la cláusula penal pactada (artículo 1593 Y 1600 C.C.C). No se
        considerará responsabilidad del COMODATARIO el deterioro derivado del uso normal y
        adecuado del bien, ni aquel causado por eventos de fuerza mayor o caso fortuito
        debidamente probados.
    </p>

    <h3>CLÁUSULA SÉPTIMA – PROHIBICIÓN DE CESIÓN Y SUBARRENDAMIENTO</h3>
    <p>
        Queda expresamente prohibido al COMODATARIO ceder, transferir, prestar o
        subarrendar el equipo a cualquier persona natural o jurídica. El incumplimiento de esta
        prohibición facultará al COMODANTE para dar por terminado el contrato de manera
        inmediata y exigir la devolución del equipo, sin perjuicio de las acciones legales
        correspondientes.
    </p>

    <h3>CLÁUSULA OCTAVA – TERMINACIÓN ANTICIPADA</h3>
    <p>
        El COMODANTE podrá dar por terminado anticipadamente el presente contrato en los
        siguientes eventos: (i) Incumplimiento de cualquiera de las obligaciones del
        COMODATARIO; (ii) Uso del equipo para fines distintos al tratamiento médico; (iii)
        Préstamo o cesión no autorizada del equipo a terceros; (iv) Cuando el COMODANTE
        requiera el bien por necesidad urgente comprobada. En estos casos, el COMODATARIO
        deberá devolver el equipo dentro de las 48 horas siguientes a la notificación. (persona
        vulnerable, afecta salud del comodatario; violación derecho a la VIDA DIGNA y Seguridad
        Social) (iv) por razones médicas justificadas o terminación del tratamiento por prescripción
        del médico tratante. (vii) fallecimiento del paciente.
    </p>

    <h3>CLÁUSULA NOVENA – NOTIFICACIONES</h3>
    <p>
        Para todos los efectos del presente contrato, las partes señalan como domicilio y medios
        de comunicación los indicados en la sección I. Cualquier cambio deberá ser notificado por
        escrito a la otra parte en un plazo no mayor a 5 días hábiles.
    </p>

    <h3>CLÁUSULA DÉCIMA – TRATAMIENTO DE DATOS PERSONALES</h3>
    <p>
        El COMODATARIO autoriza al COMODANTE para el tratamiento de sus datos
        personales, los cuales serán usados exclusivamente para la gestión del presente contrato,
        seguimiento médico del equipo y contacto en caso de emergencia, de conformidad con la
        Ley 1581 de 2012 y sus decretos reglamentarios.
    </p>

    <h3>CLÁUSULA DÉCIMA PRIMERA – JURISDICCIÓN Y SOLUCIÓN DE CONTROVERSIAS</h3>
    <p>
        Cualquier diferencia o controversia derivada de la ejecución e interpretación del presente
        contrato, será resuelta en primera instancia de manera directa y amigable entre las partes.
        De no llegarse a un acuerdo en un término de 15 días calendario, las partes se someterán
        a la jurisdicción ordinaria de los jueces civiles del domicilio del COMODANTE
    </p>

    <h3>CLÁUSULA DÉCIMA SEGUNDA – LEGISLACIÓN APLICABLE</h3>
    <p>
        El presente contrato se rige por las disposiciones del Código Civil colombiano, en especial
        las normas sobre comodato (artículos 2200 y siguientes), y demás normas concordantes
        del ordenamiento jurídico colombiano.
    </p>

    <h3>CLÁUSULA DÉCIMO - TERCERO</h3>
    <p>
        Las partes acuerdan expresamente que el presente contrato, junto con el Acta de Entrega
        debidamente firmada, para efectos de cualquier reclamación judicial derivada del
        incumplimiento, de conformidad a lo establecido en el artículo 422 del C.G.P.
    </p>

    <h3>IV. ACEPTACIÓN Y FIRMAS</h3>
    <p>
        Las partes declaran haber leído, entendido y aceptado en su totalidad el contenido del
        presente contrato, el cual se firma en dos (2) ejemplares del mismo tenor y valor, en la ciudad
        de _________________, a los _______ días del mes de _________________ de _________.
    </p>
    <!-- FIRMA Y SELLO -->
    <table style="width:100%;">
        <tr>
            <td class="firma">
                @if(!empty($profesional?->sello))
                <img src="{{ public_path('storage/'.$profesional->sello) }}"
                    style="width:80px; height:80px; object-fit:contain;" />
                @endif
                <p>COMODANTE</p>
                <p>Nombre: {{$profesional->name}}</p>
                <p>Cargo: _________________</p>
                <p>C.C. No: _________________</p>
            </td>
            <td class="firma">
                <p>COMODATARIO (Paciente/Acudiente)</p>
                <p>Nombre: _________________</p>
                <p>C.C. No: _________________</p>
                <p>Huella dactilar:</p>
            </td>
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <td colspan="2" class="firma">
                <p>TESTIGO (Quien realiza entrega)</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Nombre: _________________</p>
            </td>
            <td>
                <p>C.C.: _________________</p>
            </td>
        </tr>
    </table>

    <footer>
        Página <span class="pagenum"></span> de <span class="pagecount"></span> 
        | Documento confidencial – Uso interno
    </footer>
</body>

</html>