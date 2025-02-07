<table>
    <thead>
        <tr>
            <td>FECHA</td>
            <td>GUIA REMISION</td>
            <td>TIPO OPERACION</td>
            <td>TIPO DE CP</td>
            <td>DOCUMENTO</td>
            <td>OC</td>
            <td>CODIGO PROVEEDOR</td>
            <td>PROVEEDOR</td>
            <td>SKU</td>
            <td>FAMILIA</td>
            <td>SUB FAMILIA</td>
            <td>ARTICULO</td>
            <td>MARCA</td>
            <td>UM</td>
            <td>INGRESO</td>
            <td>PRECIO UNITARIO SOLES</td>
            <td>PRECIO UNITARIO DOLARES</td>
            <td>STOCK LOGICO</td>
            <td>OBSERVACIONES</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($Ingresos as $Ingreso)
            <tr>
                <td>{{ $Ingreso->fecha ?? '' }}</td>
                <td>{{ $Ingreso->guia_remision ?? '' }}</td>
                <td>{{ $Ingreso->transaccion->tipo_operacion ?? '' }}</td>
                <td>{{ $Ingreso->tipo_cp ?? '' }}</td>
                <td>{{ $Ingreso->documento ?? '' }}</td>
                <td>{{ $Ingreso->orden_compra ?? '' }}</td>
                <td>{{ $Ingreso->transaccion->proveedor_producto->proveedor->id ?? '' }}</td>
                <td>{{ $Ingreso->transaccion->proveedor_producto->proveedor->razon_social ?? '' }}</td>
                <td>{{ $Ingreso->transaccion->producto->SKU ?? '' }}</td>
                <td>{{ $Ingreso->transaccion->producto->articulo->sub_familia->familia->familia?? '' }}</td>
                <td>{{ $Ingreso->transaccion->producto->articulo->sub_familia->nombre?? '' }}</td>
                <td>{{ $Ingreso->transaccion->producto->articulo->nombre?? '' }}</td>
                <td>{{ $Ingreso->transaccion->marca?? '' }}</td>
                <td>{{ $Ingreso->transaccion->producto->unidad_medida->nombre?? '' }}</td>
                <td>{{ $Ingreso->numero_ingreso ?? '' }}</td>
                <td>{{ $Ingreso->transaccion->precio_unitario_soles?? '' }}</td>
                <td>{{ $Ingreso->transaccion->precio_unitario_dolares?? '' }}</td>
                <td>{{ $Ingreso->transaccion->producto->inventario->stock_logico?? '' }}</td>
                <td>{{ $Ingreso->transaccion->observaciones ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
