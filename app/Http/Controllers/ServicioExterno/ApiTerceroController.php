<?php

namespace App\Http\Controllers\ServicioExterno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiTerceroController extends Controller
{
    public function ObtenerPersonalApi($PersonaDNI)
    {
        try {
            $token = 'apis-token-13379.cSavY9e72ISAkP5ju5d6AmFz3tDaovtb';
            $dni = $PersonaDNI;

            // Iniciar llamada a API
            $curl = curl_init();
            // Buscar dni
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 2,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Referer: https://apis.net.pe/consulta-dni-api',
                    'Authorization: Bearer ' . $token
                ),
            ));

            // Ejecutar la solicitud cURL
            $response = curl_exec($curl);
            curl_close($curl);

            // Decodificar la respuesta JSON
            $persona = json_decode($response);

            // Verificar si la respuesta contiene los datos esperados
            if ($persona && isset($persona->nombres)) {
                // Devolver solo los datos necesarios
                return response()->json(['resp' => [
                    'nombres' => $persona->nombres,
                    'apellidoPaterno' => $persona->apellidoPaterno,
                    'apellidoMaterno' => $persona->apellidoMaterno,
                    'nombreCompleto' => $persona->nombres . ' ' . $persona->apellidoPaterno . ' ' . $persona->apellidoMaterno,
                ]], 200);
            }
            return response()->json(['error' => 'Persona no encontrada o respuesta inválida'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function ObtenerProveedorApi($RUC)
    {
        $token = 'apis-token-13379.cSavY9e72ISAkP5ju5d6AmFz3tDaovtb';
        $ruc = $RUC;

        // Iniciar llamada a API
        $curl = curl_init();

        // Buscar ruc sunat
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $ruc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $empresa = json_decode($response);
        return response()->json(['resp' => [
            'ruc' => $empresa->numeroDocumento,
            'razon_social' => $empresa->razonSocial,
            'direccion' => $empresa->direccion . '' . $empresa->departamento . ' - ' . $empresa->provincia . ' - ' . $empresa->distrito,
        ]], 200);
    }
}
