<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <style>
        body {
            font-family: Calibri, sans-serif;
        }

        .logo img {
            max-width: 170px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        .container {
            border: 1px solid black;
            margin-bottom: 20px;
        }

        .container2 {
            border: 1px solid black;
            margin-bottom: 20px;
        }


        .Lugar {
            padding: 5px;
            font-size: 7px
        }

        .indicaciones {
            font-size: 10px;
            gap: 100px
        }

        .indicaciones p {

            margin-bottom: -10px;
            /* Ajusta el valor según sea necesario */

        }

        .header {
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .header-table td {
            padding: 5px;
        }

        .order-details {
            text-align: right;
        }

        .order-details p {
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <table class="header-table" style="width: 100%;">
        <tr>
            <td class="logo">
                <img src="{{ public_path('Logo.jpeg') }}" alt="Logo de mi empresa">
            </td>
            <td class="order-details" style="text-align: right;">
                <table style="width: 50%; border-spacing: 0; margin: 0; display: inline-table; vertical-align: top;">
                    <tr>
                        <td colspan="2" style="text-align: left; padding: 5px 10px; line-height: 1; font-size: 7">
                            <strong>ORDEN DE COMPRA</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 3px 10px; line-height: 1;  font-size: 7">
                            <span>NÚMERO:</span>
                        </td>
                        <td style="text-align: right; padding: 3px 10px; line-height: 1; font-size: 7">
                            JWM-{{ $data['orden_compra'] }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 3px 10px; line-height: 1; font-size: 7">
                            <span>FECHA:</span>
                        </td>
                        <td style="text-align: right; padding: 3px 10px; line-height: 1; font-size: 7">
                            {{ $data['fecha'] }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



    <div class="containerHeader" style="width: 40%; margin: 0 auto; text-align: left; font-size: 9px">
        <div>
            <strong>FACTURAR A</strong>&nbsp;&nbsp;: INVERSIONES & SERVICIOS JWM SAC
        </div>
        <div>
            <strong>DIRECCIÓN</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: CAL.PIURA MZA. B LOTE. 8-A ASOC. PRO VIV.
            <span style="display: block; padding-left: 68px;">CAMPO SOL LURIGANCHO - LIMA - LIMA</span>
        </div>
        <div>
            <strong>RUC</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
            20538191176
        </div>
    </div>

    <hr style="border: none;">
    <div class="container">
        <table style="width: 100%; border-spacing: 0; border-collapse: separate;">
            <tr>
                <!-- Columna izquierda -->
                <td style="padding: 10px; vertical-align: top; text-align: left; width: 45%; padding-right: 40px;">
                    <strong>CENTRO DE COMPRA :</strong> GRUPO JWM<br><br>
                    <strong>GESTOR DE COMPRA :</strong> {{ $data['gestor_compra'] }}<br><br>
                    <strong>DETALLE
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>
                    {{ $data['detalle'] }}
                </td>
                <!-- Columna derecha -->
                <td style="padding: 10px; vertical-align: top; text-align: left; width: 55%;">
                    <div
                        style="display: flex; flex-direction: column; gap: 15px; text-align: left; padding-left: 130px;">
                        <strong>REQUERIMIENTO &nbsp;&nbsp; :</strong> {{ $data['requerimiento'] }}<br><br>
                        <strong>SOLICITANTE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>
                        {{ $data['solicitante'] }}<br><br>
                        <strong>COTIZACIÓN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong>
                        {{ $data['cotizacion'] }}
                    </div>
                </td>
            </tr>
        </table>
    </div>



    <div class="container">
        <table>
            <tr>
                <td><strong>PROVEEDOR
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> :
                    {{ $data['proveedor'] }}</td>

                <td style="vertical-align: top; text-align: left; width: 30%;">
                    <strong>TRATADO CON</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $data['tratado_con'] }}
                </td>
            </tr>
            <tr>
                <td><strong>RUC
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
                    : {{ $data['ruc'] }}</td>
                <td><strong>N° DE CELULAR &nbsp;&nbsp;&nbsp;</strong> : {{ $data['numero_celular'] }}</td>
            </tr>

            <tr>
                <td colspan="2"><strong>DIRECCIÓN
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
                    : {{ $data['direccion'] }}</td>
            </tr>
            <tr>
                <td><strong>FORMA DE PAGO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> :
                    {{ $data['forma_pago'] }}</td>
            </tr>
        </table>
        <div class="Lugar" colspan="2"><strong>LUGAR DE ENTREGA </strong> : CAL.PIURA MZA. B LOTE. 8-A ASOC. PRO
            VIV. CAMPO SOL
            LURIGANCHO - LIMA - LIMA</div>
    </div>
    <div class="container2">
        <table>
            <thead>
                <tr>
                    <td style="width: 10%;text-align: center"><strong>N° ITEM</strong></td>
                    <td style="width: 60%;text-align: center"><strong>RECURSO</strong></td>
                    <td style="width: 10%;text-align: center"><strong>UND</strong></td>
                    <td style="width: 10%;text-align: center"><strong>CANTIDAD</strong></td>
                    <td style="width: 15%;text-align: center"><strong>PRECIO</strong></td>
                    <td style="width: 10%; text-align: center"><strong>TOTAL</strong></td>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 15; $i++)
                    @if (isset($data['productos'][$i]))
                        <tr>
                            <td style="text-align: center">{{ $i + 1 }}</td>
                            <td>{{ $data['productos'][$i]['producto'] }}</td>
                            <td style="text-align: center">{{ $data['productos'][$i]['unidad'] }}</td>
                            <td style="text-align: center">{{ $data['productos'][$i]['cantidad'] }}</td>
                            <td style="text-align: center">{{ $data['moneda'] }} {{ number_format($data['productos'][$i]['precio'], 2) }}</td>
                            <td style="text-align: center">{{ $data['moneda'] }} {{ number_format($data['productos'][$i]['total'], 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td style="text-align: center">{{ $i + 1 }}</td>
                            <td colspan="5"></td>
                            <td></td>
                        </tr>
                    @endif
                @endfor

            </tbody>
            <tfoot ">
                <tr>
                    <td colspan="4" style="padding: 2px 5px; line-height: 1.2;"></td>
                    <td class="totals" style="padding: 2px 5px; line-height: 1.2;">SUB TOTAL</td>
                    <td style="padding: 2px 5px; line-height: 1.2;">{{ $data['moneda'] }} {{ number_format($data['total_productos'] / 1.18, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 2px 5px; line-height: 1.2;"></td>
                    <td class="totals" style="padding: 2px 5px; line-height: 1.2;">IGV 18%</td>
                    <td style="padding: 2px 5px; line-height: 1.2;">{{ $data['moneda'] }} {{ $data['igv'] }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 2px 5px; line-height: 1.2;"></td>
                    <td class="totals" style="padding: 2px 5px; line-height: 1.2;"></td>
                    <td style="padding: 2px 5px; line-height: 1.2;">
                        <hr style="margin: 2px 0;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 2px 5px; line-height: 1.2;">{{ $totalEnLetras }}</td>
                    <td class="totals" style="padding: 2px 5px; line-height: 1.2;"><strong>IMPORTE TOTAL</strong></td>
                    <td style="padding: 2px 5px; line-height: 1.2;"><strong>{{ $data['moneda'] }} {{ number_format($data['total_productos'], 2) }}</strong></td>
                </tr>
                

            </tfoot>
        </table>
    </div>


    <div class="indicaciones">

        <span>Indicaciones al proveedor: </span>

        <p>
            1. La Compañía y/o Empresa se reserva el derecho de la recepción de materiales, equipos y/o servicios, que
            no cumplan con las especificaciones técnicas requeridas e indicadas en la Orden y/o Contrato. La validez de
            esta Orden, esta sujeta al cumplimiento de
            las especificaciones técnicas aprobadas, que
            forman parte del expediente de la empresa.
        </p>
        <p>
            2. Al momento de la entrega de materiales, equipos y/o servicios, adjuntar fichas técnicas, manuales,
            certificados de calidad, certificados de garantía y/o
            hoja de seguridad (MSDS). Esta documentacion es indispensable para la recepción.
        </p>
        <p>
            3. En caso de entregas parciales, se podrán aceptar más de una factura por la misma Orden. No se aceptarán
            facturas que tengan más de una Orden. En
            caso el proveedor incumpla con la entrega los materiales en el plazo establecido, se le descontará una
            penalidad
            equivalente al 0.05% (del valor de la
            mercadería) por cada día de atraso, hasta un máximo de 10%.
        </p>
        <p>
            4. Para el pago de la Orden de Compra, se deberá entregar: Factura original en fisico, Guía de Remisión
            original
            con sello y firma del personal autorizado
            de recepción en Almacén. Asimismo, adjuntar copia legible de la Orden(de ser el caso). Es preciso resaltar
            que
            el plazo de pago se contabiliza desde la fecha
            de recepción de las Facturas, mas no de la fecha de emisión de las mismas.
        </p>
        <p>
            5. Horarios para la recepción de materiales, equipos y/o Facturas: (refrigerio 1:00 pm - 2:00 pm).
        </p>
        <p>
            1. Almacén / Oficina: Lunes a Viernes: 8:30 am - 5:00 pm / Sábados: 8:30 am - 12.00 pm.
        </p>
        <p>
            2. Oficina Administrativa (JWM): Lunes a Viernes 8:30 am - 5:00 pm.
        </p>
        <p>
            3. Recepción de Facturas: Lunes a Viernes: 9:00am - 5:00 pm
        </p>
        <hr style="border: none;">
        <hr style="border: none;">
        <p>
            <span>Aprovador por:&nbsp;&nbsp;&nbsp;&nbsp; Gerencia</span>

        </p>
    </div>

</body>

</html>
