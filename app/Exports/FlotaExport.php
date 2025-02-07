<?php

namespace App\Exports;

use App\Models\Area;
use App\Models\Flota;
use App\Models\Personal;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FlotaExport implements FromView, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $FlotaData = Flota::all();
        return view('excel.FlotaExport', [
            'Flotas' => $FlotaData,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Definir la tabla en Excel
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->setAutoFilter('A1:H' . $lastRow);

                // Proteger la hoja
                $event->sheet->getDelegate()->getProtection()->setPassword('JWM_Password'); // Establecer una contraseña
                $event->sheet->getDelegate()->getProtection()->setSheet(true); // Activar la protección de la hoja
                
            },
            AfterSheet::class => function (AfterSheet $event) {
                $colorVerde = 'ECFFEC';
                $colorAzul = 'ECECFF';
                $colorAzulClaro = 'FBFBFF';

                // Aplicar estilos a las celdas
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $colorVerde, // Color de fondo en hexadecimal
                        ],
                    ],
                ]);
                $event->sheet->getStyle('J1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $colorAzul, // Color de fondo en hexadecimal
                        ],
                    ],
                ]);

                // Aplicar color más claro a las celdas de la columna J desde J2 hacia abajo
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getStyle('J2:J' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $colorAzulClaro, // Color de fondo más claro en hexadecimal
                        ],
                    ],
                ]);
            },
        ];
    }
}
