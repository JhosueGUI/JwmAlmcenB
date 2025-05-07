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

        .main-table {
            /* Nueva clase para la tabla principal */
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            text-align: center;
            /* Centra el texto por defecto */
            border: 1px solid black;
            /* Añade un borde de 1px sólido negro a la tabla principal */
        }

        .main-table th,
        .main-table td {
            padding: 5px;
            text-align: center;
            /* Asegura que el texto esté centrado */
            border: none;
            /* Añade un borde de 1px sólido negro a las celdas de la tabla principal */
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .container {
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
        }

        .header {
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-collapse: separate;
            /* Para bordes separados en la tabla de encabezado */
            border-spacing: 0;
            /* Elimina el espacio entre celdas para los bordes */
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

        .footer-table {
            width: 100%;
            margin-top: 30px;
            font-size: 10px;
            border-collapse: collapse;
            /* Asegura que los bordes colapsen */
        }

        .footer-table td {
            text-align: center;
            padding: 10px;
            border: 1px solid black;
            /* Añade bordes a todas las celdas de la tabla de pie de página */
        }

        .footer-table tr:first-child td {
            border-top: 1px solid #000;
            /* Mantiene el borde superior para la primera fila */
        }
    </style>
</head>

<body>

    <table class="header-table" style="width: 100%;">
        <tr>
            <td class="logo" style="width: 30%; text-align: left;">
                <img src="{{ public_path('Logo.jpeg') }}" alt="Logo de mi empresa"
                    style="max-width: 150px; max-height: 80px;">
            </td>
            <td class="vale-salida"
                style="width: 30%; text-align: center; vertical-align: middle; font-size: 16px; font-weight: bold;">
                VALE DE SALIDA
            </td>
            <td class="order-details" style="width: 30%; text-align: right;">
                <table
                    style="width: 70%; border-collapse: collapse; margin-left: auto; display: inline-table; vertical-align: top;">
                    <tr>
                        <td colspan="2" style="text-align: left; padding: 5px 10px; line-height: 1; font-size: 7;">
                            <strong></strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 3px 10px; line-height: 1; font-size: 7;">
                            <span>NÚMERO:</span>
                        </td>
                        <td style="text-align: right; padding: 3px 10px; line-height: 1; font-size: 7;">
                            {{ $numero_vale }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 3px 10px; line-height: 1; font-size: 7;">
                            <span>FECHA:</span>
                        </td>
                        <td style="text-align: right; padding: 3px 10px; line-height: 1; font-size: 7;">
                            {{ $fecha_vale }}
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <div class="containerHeader" style="width: 40%; margin: 0 auto; text-align: left; font-size: 9px">
        <div>
            <strong>EMPRESA</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: INVERSIONES & SERVICIOS JWM SAC
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
    <hr style="border: none;">
    <hr style="border: none;">
    <table class="main-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Cantidad</th>
                <th>UNM</th>
                <th>NOMBRE DEL PRODUCTO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lineas_salida as $index => $linea)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $linea['cantidad'] }}</td>
                    <td>{{ $linea['unidad_medida'] }}</td>
                    <td>{{ $linea['nombre_producto'] }}</td>
                </tr>
            @endforeach

            @for ($i = count($lineas_salida); $i < 20; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endfor
        </tbody>

    </table>

    <table class="footer-table">
        <tr>
            <td>UNIDAD</td>
            <td>KM || HR</td>
            <td>PERSONAL</td>
            <td>FIRMA</td>
        </tr>
        <tr>
            <td style="border-top: none; padding-bottom: 30px;">{{ $unidad }}</td>
            <td style="border-top: none; padding-bottom: 30px;">{{ $kilometraje_horometro }}</td>
            <td style="border-top: none; padding-bottom: 30px;">{{ $personal }}</td>
            <td style="border-top: none; padding-bottom: 30px; width: 200px;"></td>
        </tr>
    </table>
    <div style="text-align: right; margin-top: 20px; font-weight: bold;">
        DESTINO : {{ $destino }}
    </div>

</body>

</html>
