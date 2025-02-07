<table>
    <thead>
        <tr>
            <td>FECHA</td>
            <td>VALE</td>
            <td>TIPO OPERACION</td>
            <td>DESTINO</td>
            <td>PERSONAL</td>
            <td>UNIDAD</td>
            <td>DURACION DE NEUMATICO</td>
            <td>KILOMETRAJE / HOROMETRAJE</td>
            <td>FECHA DE VENCIMIENTO</td>
            <td>SKU</td>
            <td>FAMILIA</td>
            <td>SUB FAMILIA</td>
            <td>ARTICULO</td>
            <td>MARCA</td>
            <td>UM</td>
            <td>SALIDA</td>
            <td>PRECIO UNITARIO SOLES</td>
            <td>PRECIO UNITARIO DOLARES</td>
            <td>STOCK LOGICO</td>
            <td>OBSERVACIONES</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($Salidas as $Salida)
            <tr>
                <td>{{ $Salida->fecha ?? '' }}</td>
                <td>{{ $Salida->vale ?? '' }}</td>
                <td>{{ $Salida->transaccion->tipo_operacion ?? '' }}</td>
                <td>{{ $Salida->destino ?? '' }}</td>
                <td>{{ $Salida->personal->persona->nombre ?? '' }} {{ $Salida->personal->persona->apellido_paterno ?? '' }}</td>
                <td>{{ $Salida->unidad ?? '' }}</td>
                <td>{{ $Salida->duracion_neumatico ?? '' }}</td>
                <td>{{ $Salida->kilometraje_horometro ?? '' }}</td>
                <td>{{ $Salida->fecha_vencimiento ?? '' }}</td>
                <td>{{ $Salida->transaccion->producto->SKU ?? '' }}</td>
                <td>{{ $Salida->transaccion->producto->articulo->sub_familia->familia->familia ?? '' }}</td>
                <td>{{ $Salida->transaccion->producto->articulo->sub_familia->nombre ?? '' }}</td>
                <td>{{ $Salida->transaccion->producto->articulo->nombre ?? '' }}</td>
                <td>{{ $Salida->transaccion->marca ?? '' }}</td>
                <td>{{ $Salida->transaccion->producto->unidad_medida->nombre ?? '' }}</td>
                <td>{{ $Salida->numero_salida ?? '' }}</td>
                <td>{{ $Salida->transaccion->precio_total_soles ?? '' }}</td>
                <td>{{ $Salida->transaccion->precio_total_dolares ?? '' }}</td>
                <td>{{ $Salida->transaccion->producto->inventario->stock_logico ?? '' }}</td>
                <td>{{ $Salida->transaccion->observaciones ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
