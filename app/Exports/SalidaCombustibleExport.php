<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class SalidaCombustibleExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $dataForExport;

    public function __construct($dataForExport)
    {
        $this->dataForExport = $dataForExport;
    }

    public function view(): View
    {
        return view('excel.SalidaCombustibleExport', [
            'SalidaCombustibles' => $this->dataForExport,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Definir la tabla en Excel
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->setAutoFilter('A2:J' . $lastRow);

                // Proteger la hoja
                $event->sheet->getDelegate()->getProtection()->setPassword('JWM'); // Establecer una contraseña
                $event->sheet->getDelegate()->getProtection()->setSheet(true); // Activar la protección de la hoja

                $event->sheet->getStyle('A1:J'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,  // Establecer borde grueso
                            'color' => ['argb' => '000000'], // Color negro
                        ],
                    ],
                ]);

                $event->sheet->getStyle('N2:T'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,  // Establecer borde grueso
                            'color' => ['argb' => '000000'], // Color negro
                        ],
                    ],
                ]);

                // Colores que vas a usar para las filas
                $colors = [
                    'F9F9F9', // Color claro
                    'D9D9D9', // Color gris
                ];

                // Aplicar el color a cada fila (comenzando desde la fila 2)
                for ($row = 2; $row <= $lastRow; $row++) {
                    // Escoger un color para cada fila, alternando los colores
                    $color = $colors[($row - 2) % count($colors)];

                    // Aplicar el color a las celdas de la fila
                    $event->sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => $color,
                            ],
                        ],
                    ]);
                }
                for ($row = 2; $row <= $lastRow; $row++) {
                    // Escoger un color para cada fila, alternando los colores
                    $color = $colors[($row - 2) % count($colors)];

                    // Aplicar el color a las celdas de la fila
                    $event->sheet->getStyle("N{$row}:T{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => $color,
                            ],
                        ],
                    ]);
                }

                // Otros estilos para encabezados o celdas específicas
                $colorHex = '0B1C51';
                $ColorPlaca = 'FC9F30';
                $ColorCombustible = 'F8D3AB';

                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $colorHex,
                        ],
                    ],
                ]);
                $event->sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $ColorPlaca,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('H2:J2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $ColorCombustible,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('B2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $ColorCombustible,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('E2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $ColorCombustible,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('F2:G2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $ColorPlaca,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('C2:D2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $ColorPlaca,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('N2:T2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $ColorCombustible,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
            },
        ];
    }
}
