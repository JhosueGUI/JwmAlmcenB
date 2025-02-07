<?php

namespace App\Imports;

use App\Models\Articulo;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Producto;
use App\Models\SubFamilia;
use App\Models\Ubicacion;
use App\Models\UnidadMedida;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class InventarioImport implements ToCollection
{
    /**
     * Maneja la importación de datos desde una colección de Excel.
     *
     * @param Collection $collection La colección de datos de la hoja de Excel.
     */
    public function collection(Collection $columnas)
    {
        // Inicia una transacción para asegurar que todas las operaciones se realicen correctamente.
        DB::transaction(function () use ($columnas) {
            // Obtiene todos los registros de InventarioValorizado que están activos y los organiza por SKU del producto.
            $inventarioExistentes = InventarioValorizado::where('estado_registro', 'A')
                ->get()
                ->keyBy(fn ($item) => $item->inventario->producto->SKU);

            // Itera sobre cada fila de datos en la colección.
            foreach ($columnas as $index => $columna) {
                // Salta la primera fila (generalmente encabezado) o filas con menos de 6 columnas.
                if ($index === 0 || count($columna) < 6) {
                    Log::warning('Fila con datos incompletos o de encabezado: ' . json_encode($columna));
                    continue; // Salta a la siguiente fila.
                }

                // Extrae los valores de cada columna.
                $nombre_ubicacion = $columna[0] ?? null;
                $sku = $columna[1] ?? null;
                $familia = $columna[2] ?? null;
                $nombre_sub_familia = $columna[3] ?? null;
                $nombre_articulo = $columna[4] ?? null;
                $nombre_unidad_medida = $columna[5] ?? null;

                // Busca la unidad de medida, sub-familia y ubicación en la base de datos.
                $unidad_medida = $nombre_unidad_medida ? UnidadMedida::where('estado_registro', 'A')->where('nombre', $nombre_unidad_medida)->first() : null;
                $sub_familia = $nombre_sub_familia ? SubFamilia::where('estado_registro', 'A')->where('nombre', $nombre_sub_familia)->first() : null;
                $ubicacion = $nombre_ubicacion ? Ubicacion::where('estado_registro', 'A')->where('codigo_ubicacion', $nombre_ubicacion)->first() : null;

                // Si la ubicación es una cadena vacía o no se encuentra, se asigna null al identificador de la ubicación.
                $ubicacion_id = ($ubicacion && $nombre_ubicacion !== "") ? $ubicacion->id : null;

                // Si no se encuentran la unidad de medida o la sub-familia, se registra una advertencia y se salta la fila.
                if (!$unidad_medida || !$sub_familia) {
                    Log::warning('Datos de referencia no encontrados: ' . json_encode($columna));
                    continue;
                }

                // Si el SKU no existe en los registros de InventarioValorizado, se crean los nuevos registros.
                if (!$inventarioExistentes->has($sku)) {
                    // Crea un nuevo Articulo.
                    $articulo = Articulo::create([
                        'nombre' => $nombre_articulo,
                        'sub_familia_id' => $sub_familia->id
                    ]);

                    // Crea un nuevo Producto asociado con el Articulo creado.
                    $producto = Producto::create([
                        'SKU' => $sku,
                        'articulo_id' => $articulo->id,
                        'unidad_medida_id' => $unidad_medida->id
                    ]);

                    // Crea un nuevo Inventario asociado con el Producto y la Ubicación.
                    $inventario = Inventario::create([
                        'producto_id' => $producto->id,
                        'ubicacion_id' => $ubicacion_id
                    ]);

                    // Crea un nuevo InventarioValorizado asociado con el Inventario.
                    InventarioValorizado::create([
                        'inventario_id' => $inventario->id
                    ]);
                }
            }
        });
    }
}
