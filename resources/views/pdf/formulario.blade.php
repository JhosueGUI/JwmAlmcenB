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
                            <strong>FORMULARIO</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 3px 10px; line-height: 1;  font-size: 7">
                            <span>NÚMERO:</span>
                        </td>
                        <td style="text-align: right; padding: 3px 10px; line-height: 1; font-size: 7">
                            JWM-{{ $data['id'] }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; padding: 3px 10px; line-height: 1; font-size: 7">
                            <span>FECHA:</span>
                        </td>
                        {{-- <td style="text-align: right; padding: 3px 10px; line-height: 1; font-size: 7">
                            {{ $data['fecha'] }}
                        </td> --}}
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
    <div class="container2">
        <h4><strong>Datos del Conductor</strong></h4>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 8px;">
            <div>
                <strong>Nombre Completo:</strong>
                {{ $personal['persona']['nombre'] ?? '' }}
                {{ $personal['persona']['apellido_paterno'] ?? '' }}
                {{ $personal['persona']['apellido_materno'] ?? '' }}
                <strong> DNI:</strong>
                {{ $personal['persona']['numero_documento'] ?? '' }}
            </div>
        </div>




        {{-- Bloque 1: Información del Vehículo --}}
        <h4><strong>Información del Vehículo</strong></h4>
        @for ($i = 0; $i < 4; $i++)
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 8px;">
                @if (isset($preguntas[$i]))
                    <div>
                        {{ $preguntas[$i] }}:
                        @if (isset($respuestas[$i]))
                            {{ $respuestas[$i] }}
                            @if ($i == 1)
                                Km
                            @elseif ($i == 2)
                                Hr
                            @endif
                        @else
                            No respondida
                        @endif
                    </div>
                @else
                    <div><em>Sin pregunta</em></div>
                @endif
            </div>
        @endfor

        <h4><strong>Descripción de la Falla</strong></h4>
        @for ($i = 4; $i < 8; $i++)
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 8px;">
                @if (isset($preguntas[$i]))
                    <div>
                        {{ $preguntas[$i] }}:
                        {{ isset($respuestas[$i]) ? $respuestas[$i] : 'No respondida' }}
                    </div>
                @else
                    <div><em>Sin pregunta</em></div>
                @endif
            </div>
        @endfor


    </div>


</body>

</html>
