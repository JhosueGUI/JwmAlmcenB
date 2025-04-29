<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Acceso;
use App\Models\AccesoRol;
use App\Models\Rol;
use App\Models\User;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            // Obteniendo credenciales del request
            $credentials = $request->only('username', 'password');

            // Verificando si el usuario existe y está activo
            $user = User::with('personal.persona.tipo_documento', 'user_rol.rol.acceso_rol.acceso.sub_acceso.sub_acceso')
                ->where('username', $request->username)
                ->where('estado_registro', 'A')
                ->first();
            // return response()->json($user);

            if (!$user) {
                return response()->json(["error" => "El nombre de usuario no existe"], 400);
            }

            try {
                // Cambiando la duración del token (si es necesario)
                $this->cambiarDuracionToken();

                // Intentando generar un token JWT
                if (!$token = FacadesJWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Credenciales inválidas'], 403);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'No se pudo crear el token'], 500);
            }
            // return response()->json($user);
            $roles = $user->user_rol->map(function ($userRol) {
                return [
                    'id' => $userRol->rol->id,
                    'nombre' => $userRol->rol->nombre,
                ];
            });
            $accesos = $user->user_rol->flatMap(function ($userRol) {
                return $userRol->rol->acceso_rol->map(function ($accesoRol) {
                    return $accesoRol->acceso;
                });
            });
            $accesosFormateados = $accesos->filter(function ($acceso) {
                return $acceso->acceso_padre_id === null;
            })->map(function ($acceso) use ($accesos) {
                // Obtenemos los sub-accesos del acceso actual
                $subAccesos = $accesos->filter(function ($sub) use ($acceso) {
                    return $sub->acceso_padre_id == $acceso->id;
                })->values();
            
                return [
                    "id" => $acceso->id,
                    "nombre" => $acceso->nombre,
                    "tipo_acceso" => $acceso->tipo_acceso,
                    "ruta" => $acceso->ruta,
                    "acceso_padre_id" => $acceso->acceso_padre_id,
                    "estado_registro" => $acceso->estado_registro,
                    "sub_acceso" => $subAccesos->map(function ($sub) use ($accesos) {
                        // Aquí podrías seguir buscando sub-sub-accesos si fuera necesario
                        $subSubAccesos = $accesos->filter(function ($subSub) use ($sub) {
                            return $subSub->acceso_padre_id == $sub->id;
                        })->values();
            
                        return [
                            "id" => $sub->id,
                            "nombre" => $sub->nombre,
                            "tipo_acceso" => $sub->tipo_acceso,
                            "ruta" => $sub->ruta,
                            "acceso_padre_id" => $sub->acceso_padre_id,
                            "estado_registro" => $sub->estado_registro,
                            "sub_acceso" => $subSubAccesos->map(function ($sSub) {
                                return [
                                    "id" => $sSub->id,
                                    "nombre" => $sSub->nombre,
                                    "tipo_acceso" => $sSub->tipo_acceso,
                                    "ruta" => $sSub->ruta,
                                    "acceso_padre_id" => $sSub->acceso_padre_id,
                                    "estado_registro" => $sSub->estado_registro,
                                    "sub_acceso" => [], // Si no hay más niveles, se queda vacío
                                ];
                            })->toArray(),
                        ];
                    })->toArray(),
                ];
            })->values()->toArray();
            // Preparando la respuesta
            $response = [
                "id" => $user->id,
                "personal_id" => $user->personal_id,
                "persona" => $user->personal->persona,
                "username" => $user->username,
                "roles" => $roles,
                "accesos" => $accesosFormateados,
                "token" => $token
            ];


            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }


    private function cambiarDuracionToken()
    {
        $myTTL = 60 * 24 * 1; // En minutos
        FacadesJWTAuth::factory()->setTTL($myTTL);
    }
    public function cambiarPassword(Request $request)
    {
        try {
            DB::beginTransaction();
            // Obtén el usuario autenticado
            $usuarioIngresado = User::where('id', auth()->user()->id)->first();

            // Obtén las contraseñas desde el request
            $contraseña_actual = $request->input('contraseña_actual');

            // Verifica que la contraseña actual ingresada coincida con la almacenada
            if (Hash::check($contraseña_actual, $usuarioIngresado->password)) {
                $nueva_contraseña = $request->input('nueva_contraseña');
                $repetir_contraseña = $request->input('repetir_contraseña');
                if ($nueva_contraseña === $repetir_contraseña) {
                    $usuarioIngresado->update([
                        "password" => $nueva_contraseña
                    ]);
                    $usuarioIngresado->password = Hash::make($nueva_contraseña);
                } else {
                    return response()->json(["error" => "Las Nuevas Contraseñas Ingresadas no coinciden"], 500);
                }
            } else {
                return response()->json(["error" => "Contraseña actual incorrecta"], 500);
            }
            DB::commit();
            return response()->json(['resp' => "Contraseña Actualizado Correctamente"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
