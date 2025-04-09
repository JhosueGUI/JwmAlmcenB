<?php

namespace App\Http\Controllers\Formulario;

use App\Http\Controllers\Controller;
use App\Models\Flota;
use App\Models\Formulario;
use App\Models\Personal;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class PreguntaController extends Controller
{
    public function get()
    {
        try {
            $forms = Formulario::with(['preguntas.respuestas'])->get();

            $placasUnicas = [];

            foreach ($forms as $form) {
                $placa = null;
                foreach ($form->preguntas as $pregunta) {
                    if ($pregunta->pregunta === 'Placa' && $pregunta->respuestas->isNotEmpty()) {
                        $placa = $pregunta->respuestas->first()->respuesta;
                        break;
                    }
                }

                if ($placa) {
                    if (!isset($placasUnicas[$placa])) {
                        $placasUnicas[$placa] = [
                            'placa' => $placa,
                            'id' => $form->id, // Se actualizará al final
                            'personal_id' => $form->personal_id, // Se actualizará al final
                            'titulo' => $form->titulo, // Se actualizará al final
                            'descripcion' => $form->descripcion, // Se actualizará al final
                            'url_pdf' => $form->url_pdf, // Se actualizará al final
                            'estado_registro' => $form->estado_registro, // Se actualizará al final
                            'detalles' => [],
                        ];
                    } else {
                        // Agregar el formulario actual a los detalles antes de actualizar los principales
                        $placasUnicas[$placa]['detalles'][] = [
                            'id' => $placasUnicas[$placa]['id'],
                            'personal_id' => $placasUnicas[$placa]['personal_id'],
                            'titulo' => $placasUnicas[$placa]['titulo'],
                            'descripcion' => $placasUnicas[$placa]['descripcion'],
                            'url_pdf' => $placasUnicas[$placa]['url_pdf'],
                            'estado_registro' => $placasUnicas[$placa]['estado_registro'],
                        ];
                    }

                    // Actualizar los datos principales con el formulario actual (el último encontrado)
                    $placasUnicas[$placa]['id'] = $form->id;
                    $placasUnicas[$placa]['personal_id'] = $form->personal_id;
                    $placasUnicas[$placa]['titulo'] = $form->titulo;
                    $placasUnicas[$placa]['descripcion'] = $form->descripcion;
                    $placasUnicas[$placa]['url_pdf'] = $form->url_pdf;
                    $placasUnicas[$placa]['estado_registro'] = $form->estado_registro;
                }
            }

            // Convertir el array asociativo a un array de objetos
            $resultado = array_values($placasUnicas);

            return response()->json(['data' => $resultado], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $personal = Personal::with('persona')->where('id', $user->personal_id)->first();
            // return $personal;
            $formulario = Formulario::create([
                'personal_id' => $user->personal_id,
                'titulo' => 'Formulario',
                'descripcion' => 'Reporte de Fallas - Área de Mantenimiento',
            ]);

            $preguntas = [
                'Placa',
                'Kilometraje actual',
                'Horómetro actual',
                'Fecha y hora del reporte',
                'Tipo de falla',
                'Descripción de la falla',
                'Ubicación donde ocurrió la falla',
                '¿La falla impide la operación del vehículo?',
            ];
            $respuestasRequest = $request->input('respuestas');
            $contador = 0;
            foreach ($preguntas as $preguntaTexto) { // Cambiamos el nombre de la variable a $preguntaTexto para evitar confusión
                $pregunta = Pregunta::create([
                    'formulario_id' => $formulario->id,
                    'pregunta' => $preguntaTexto,
                ]);

                // Verificamos si existe una respuesta para la pregunta actual usando el contador
                if (isset($respuestasRequest[$contador])) {
                    Respuesta::create([
                        'pregunta_id' => $pregunta->id,
                        'respuesta' => $respuestasRequest[$contador],
                    ]);
                }
                $contador++; // Incrementamos el contador para la siguiente pregunta
            }

            //renderizar vista
            $html = view('pdf.formulario', [
                'data' => $formulario,
                'personal' => $personal,
                'preguntas' => $preguntas,
                'respuestas' => $respuestasRequest,
            ])->render();

            //generar pdf
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $output = $pdf->output();

            //guardar el pdf
            $pdf_nombre = $formulario->id . '-formulario.pdf';

            Storage::disk('public')->put("formulario/pdf/" . $pdf_nombre, $output);

            DB::commit();
            $ruta_archivo = asset("storage/formulario/pdf/" . $pdf_nombre);

            $formulario->update([
                'url_pdf' => $ruta_archivo,
            ]);

            return response()->json(["resp" => "Formulario y respuestas creadas correctamente.", $ruta_archivo], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function createSinLogin(Request $request)
    {
        try {
            DB::beginTransaction();
            $personal = Personal::with('persona')->where('id', $request->personal_id)->first();
            if (!$personal) {
                return response()->json(["error" => "Personal no encontrado"], 404);
            }
            // return $personal;
            $formulario = Formulario::create([
                'personal_id' => $personal->id,
                'titulo' => 'Formulario',
                'descripcion' => 'Reporte de Fallas - Área de Mantenimiento',
            ]);

            $preguntas = [
                'Placa',
                'Kilometraje actual',
                'Horómetro actual',
                'Fecha y hora del reporte',
                'Tipo de falla',
                'Descripción de la falla',
                'Ubicación donde ocurrió la falla',
                '¿La falla impide la operación del vehículo?',
            ];
            $respuestasRequest = $request->input('respuestas');
            $contador = 0;
            foreach ($preguntas as $preguntaTexto) { // Cambiamos el nombre de la variable a $preguntaTexto para evitar confusión
                $pregunta = Pregunta::create([
                    'formulario_id' => $formulario->id,
                    'pregunta' => $preguntaTexto,
                ]);

                // Verificamos si existe una respuesta para la pregunta actual usando el contador
                if (isset($respuestasRequest[$contador])) {
                    Respuesta::create([
                        'pregunta_id' => $pregunta->id,
                        'respuesta' => $respuestasRequest[$contador],
                    ]);
                }
                $contador++; // Incrementamos el contador para la siguiente pregunta
            }

            //renderizar vista
            $html = view('pdf.formulario', [
                'data' => $formulario,
                'personal' => $personal,
                'preguntas' => $preguntas,
                'respuestas' => $respuestasRequest,
            ])->render();

            //generar pdf
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $output = $pdf->output();

            //guardar el pdf
            $pdf_nombre = $formulario->id . '-formulario.pdf';

            Storage::disk('public')->put("formulario/pdf/" . $pdf_nombre, $output);

            DB::commit();
            $ruta_archivo = asset("storage/formulario/pdf/" . $pdf_nombre);

            $formulario->update([
                'url_pdf' => $ruta_archivo,
            ]);

            return response()->json(["resp" => "Formulario y respuestas creadas correctamente.", $ruta_archivo], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function descargarPdf($formulario)
    {
        try {
            // Generar el nombre del archivo PDF basado en el número de orden de compra
            $pdf_nombre = $formulario . '-formulario.pdf';

            // Verificar si el archivo PDF existe
            $pdfPath = storage_path('app/public/formulario/pdf/' . $pdf_nombre);       // Ruta completa al archivo PDF

            if (!file_exists($pdfPath)) {
                return response()->json(['error' => 'El archivo PDF no existe.'], 404);
            }
            // Descargar el archivo PDF
            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($pdfPath, $pdf_nombre, $headers);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
