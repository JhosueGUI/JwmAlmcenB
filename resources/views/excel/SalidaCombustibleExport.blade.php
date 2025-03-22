<!DOCTYPE html>
<html>

<head>
    <title>Salida Combustible</title>
    <style>
        .header-row {
            text-align: center;
        }

        .logo img {
            max-width: 10px;
        }

        .header-row {
            display: flex;
        }
    </style>
</head>

<body>
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr class="header-row">
                <td colspan="9" style="text-align: center;" class="header-row">
                    <h1>Salida de Combustible</h1>
                </td>
            </tr>


            <tr>
                <th><strong>Placa</strong></th>
                <th><strong>Fecha</strong></th>
                <th><strong>Kilometraje</strong></th>
                <th><strong>Horometro</strong></th>
                <th><strong>Personal</strong></th>
                <th><strong>Combustible Stock</strong></th>
                <th><strong>Combustible Ruta</strong></th>
                <th><strong>Precio Unitario Soles</strong></th>
                <th><strong>Precio Total Soles</strong></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><strong>Destino</strong></th>
                <th><strong>Contómetro</strong></th>
                <th><strong>Margen Error</strong></th>
                <th><strong>Resultado</strong></th>
                <th><strong>Precinto Nuevo</strong></th>
                <th><strong>Precinto Anterior</strong></th>
                <th><strong>Observación</strong></th>




            </tr>
        </thead>
        <tbody>
            @foreach ($SalidaCombustibles as $salida)
                @foreach ($salida['detalle'] as $detalle)
                    <tr>
                        <td>{{ $salida['placa'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($detalle['fecha'])->format('d/m/y') }}</td>
                        <td>{{ $detalle['kilometraje']}}</td>
                        <td>{{ $detalle['horometro']}}</td>
                        <td>{{ $detalle['personal'] }}</td>
                        <td>{{ $detalle['numero_salida_stock'] }}</td>
                        <td>{{ $detalle['numero_salida_ruta'] }}</td>
                        <td>S/. {{ $detalle['precio_unitario_soles'] }}</td>
                        <td>S/. {{ $detalle['precio_total_soles'] }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$detalle['destino']}}</td>
                        <td>{{$detalle['contometro']}}</td>
                        <td>{{$detalle['margen_error']}}</td>
                        <td>{{$detalle['resultado']}}</td>
                        <td>{{$detalle['precinto_nuevo']}}</td>
                        <td>{{$detalle['precinto_anterior']}}</td>
                        <td>{{$detalle['observacion']}}</td>

                    </tr>
                @endforeach
            @endforeach

        </tbody>
    </table>
</body>

</html>
