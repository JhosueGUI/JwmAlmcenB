<table>
    <thead>
        <tr>
            <td>UBICACION</td>
            <td>SKU</td>
            <td>FAMILIA</td>
            <td>SUB FAMILIA</td>
            <td>ARTICULO</td>
            <td>UNIDAD DE MEDIDA</td>
            <td>NUMEROS DE SALIDA</td>
            <td>NUMEROS DE INGRESO</td>
            <td>STOCK</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($Inventarios as $Inventario)
            <tr>
                <td>{{ $Inventario->ubicacion->codigo_ubicacion ?? ''}}</td>
                <td>{{ $Inventario->producto->SKU ?? ''}}</td>
                <td>{{ $Inventario->producto->articulo->sub_familia->familia->familia ?? ''}}</td>
                <td>{{ $Inventario->producto->articulo->sub_familia->nombre ?? ''}}</td>
                <td>{{ $Inventario->producto->articulo->nombre ?? ''}}</td>
                <td>{{ $Inventario->producto->unidad_medida->nombre ?? ''}}</td>
                <td>{{ $Inventario->total_salida ?? ''}}</td>
                <td>{{ $Inventario->total_salida ?? ''}}</td>
                <td>{{ $Inventario->stock_logico ?? ''}}</td>
            </tr>  
        @endforeach
    </tbody>
</table>
