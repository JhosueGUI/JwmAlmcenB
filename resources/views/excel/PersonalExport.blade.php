<table>
    <thead>
        <tr>
            <th>NOMBRE</th>
            <th>APELLIDO PATERNO</th>
            <th>APELLIDO MATERNO</th>
            <th>GMAIL</th>
            <th>TIPO DE DOCUMENTO</th>
            <th>NUMERO DOCUMENTO</th>
            <th>AREA</th>
            <th>HABILIDAD</th>
            <th>EXPERIENCIA</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($Personales as $Personal)
            <tr>
                <td>{{ $Personal->persona->nombre ?? '' }}</td>
                <td>{{ $Personal->persona->apellido_paterno ?? '' }}</td>
                <td>{{ $Personal->persona->apellido_materno?? ''  }}</td>
                <td>{{ $Personal->persona->gmail ?? '' }}</td>
                <td>{{ $Personal->persona->tipo_documento->nombre?? ''  }}</td>
                <td>{{ $Personal->persona->numero_documento ?? '' }}</td>
                <td>{{ $Personal->area->nombre ?? '' }}</td>
                <td>{{ $Personal->habilidad ?? '' }}</td>
                <td>{{ $Personal->experiencia ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>