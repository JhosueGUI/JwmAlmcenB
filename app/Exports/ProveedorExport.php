<?php

namespace App\Exports;

use App\Models\Proveedor;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProveedorExport implements FromView, ShouldAutoSize,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View 
    {
        $ProveedorData = Proveedor::all();
        
        return view('excel.ProveedorExport', [
            'Proveedores' => $ProveedorData,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Definir la tabla en Excel
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->setAutoFilter('A1:H'.$lastRow);
                
                // Proteger la hoja
                $event->sheet->getDelegate()->getProtection()->setPassword('JWM_Password'); // Establecer una contraseña
                $event->sheet->getDelegate()->getProtection()->setSheet(true); // Activar la protección de la hoja
            },
            AfterSheet::class => function(AfterSheet $event) {
                $colorHex = 'FFECEC';
                // Aplicar estilos a las celdas
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => $colorHex, // Color de fondo en hexadecimal (ejemplo: rojo)
                        ],
                    ],
                ]);
            },
        ];
    }
}
