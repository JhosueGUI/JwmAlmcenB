<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        $tipo_documentos = [
            'DNI',
            'RUC'
        ];
        $descripciones = [
            'Documento Nacional de Indentidad',
            'Registro Unico Contribuyente'
        ];
        foreach ($tipo_documentos as $index => $tipo_documento) {
            TipoDocumento::firstOrcreate([
                'nombre' => $tipo_documento,
                'descripcion' => $descripciones[$index]
            ]);
        }
    }
}
