<table>
    <thead>
        <tr>
            <td>PLACA</td>
            <td>CONDUCTOR</td>
            <td>TIPO</td>
            <td>MARCA</td>
            <td>MODELO</td>
            <td>EMPRESA</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($Flotas as $Flota)
            <tr>
                <td>{{ $Flota->placa ?? '' }}</td>
                <td>{{ $Flota->personal->persona->nombre ?? '' }} {{ $Flota->personal->persona->apellido_paterno ?? "SIN CONDUCTOR" }}</td>
                <td>{{ $Flota->tipo ?? '' }}</td>
                <td>{{ $Flota->marca ?? '' }}</td>
                <td>{{ $Flota->modelo ?? '' }}</td>
                <td>{{ $Flota->empresa ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
