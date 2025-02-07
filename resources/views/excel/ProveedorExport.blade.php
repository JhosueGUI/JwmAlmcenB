<table>
    <thead>
        <tr>
            <td>PROVEEDOR</td>
            <td>RUC</td>
            <td>DIRECCIÓN FISCAL</td>
            <td>FORMA DE PAGO</td>
            <td>CONTACTO</td>
            <td>NÚMERO CELULAR</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($Proveedores as $Proveedor)
            <tr>
                <td>{{ $Proveedor->razon_social ?? '' }}</td>
                <td>{{ $Proveedor->ruc ?? '' }}</td>
                <td>{{ $Proveedor->direccion ?? '' }}</td>
                <td>{{ $Proveedor->forma_pago ?? '' }}</td>
                <td>{{ $Proveedor->contacto ?? '' }} {{ $Salida->personal->persona->apellido_paterno ?? '' }}</td>
                <td>{{ $Proveedor->numero_celular ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>