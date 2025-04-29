<?php

namespace App\Http\Controllers;

use App\Exports\PersonalExport;
use App\Mail\CredencialesMail;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\Rol;
use App\Models\User;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Imports\PersonalImport;
use App\Models\Area;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PersonalController extends Controller
{
    public function get()
    {
        try {
            $personal = Personal::with('persona', 'cargo.area.horario', 'planilla')->where('estado_registro', 'A')->get();
            if (!$personal) {
                return response()->json(['resp' => 'Personal no existentes'], 200);
            }
            return response()->json(['data' => $personal], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getPersonalDisable(){
        try{
            $personalDisable=Personal::with('persona', 'cargo.area.horario', 'planilla')->where('estado_registro', 'I')->get();
            if (!$personalDisable) {
                return response()->json(['resp' => 'Personal no existentes'], 200);
            }
            return response()->json(['data' => $personalDisable], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function reactivarPeronal($idPersonal){
        try{
            $personalDisable=Personal::where('id',$idPersonal)->where('estado_registro','I')->first();
            $personaDisable=Persona::where('id',$personalDisable->persona_id)->where('estado_registro','I')->first();
            $userDisable=User::where('personal_id',$personalDisable->id)->where('estado_registro','I')->first();
            $user_rolDisable=UsuarioRol::where('user_id',$userDisable->id)->where('estado_registro','I')->first();
            if (!$personalDisable) {
                return response()->json(['resp' => 'Personal no existentes'], 200);
            }
            $personalDisable->update([
                'estado_registro'=>'A'
            ]);
            $personaDisable->update([
                'estado_registro'=>'A'
            ]);
            $userDisable->update([
                'estado_registro'=>'A'
            ]);
            $user_rolDisable->update([
                'estado_registro'=>'A'
            ]);

            return response()->json(['resp' => 'Personal Activado Correctamente'], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getPersonalTransaccion()
    {
        try {
            $personal = Personal::with(['persona', 'area', 'salida'])->where('estado_registro', 'A')->whereHas('salida')->get();
            if (!$personal) {
                return response()->json(['resp' => 'Personal no existentes'], 200);
            }
            return response()->json(['data' => $personal], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getPersonalFlota()
    {
        try {
            $area = Area::where('estado_registro', 'A')->where('nombre', 'FLOTA')->first();
            if (!$area) {
                return response()->json(['resp' => 'Area no existe'], 200);
            }
            $personal_flota = Personal::with('persona')->where('estado_registro', 'A')->where('area_id', $area->id)->get();
            if (!$personal_flota) {
                return response()->json(['resp' => 'Personal no existentes'], 200);
            }
            return response()->json(['data' => $personal_flota]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function show($personalID)
    {
        try {
            $personal = Personal::where('estado_registro', 'A')->find($personalID);
            if (!$personal) {
                return response()->json(['resp' => 'Personal no existe'], 200);
            }
            return response()->json(['resp' => $personal], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $existePersona = Persona::where('estado_registro', 'A')->where('numero_documento', $request->numero_documento)->first();
            if ($existePersona) {
                return response()->json(['resp' => 'El número de documento ya está en uso por otra persona'], 500);
            }
            if (!$request->tipo_documento_id) {
                return response()->json(['resp' => 'Seleccione un Tipo de Documento'], 500);
            }
            if (!$request->numero_documento) {
                return response()->json(['resp' => 'Ingrese el número de Documento'], 500);
            }
            $persona = Persona::updateOrCreate([
                'numero_documento' => $request->numero_documento
            ], [
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'gmail' => $request->gmail,
                'tipo_documento_id' => $request->tipo_documento_id,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'estado_registro' => 'A',
            ]);
            $personal = Personal::updateOrCreate([
                'persona_id' => $persona->id,
            ], [
                'cargo_id' => $request->cargo_id,
                'habilidad' => $request->habilidad,
                'experiencia' => $request->experiencia,
                'fecha_ingreso' => $request->fecha_ingreso,
                'planilla_id' => $request->planilla_id,
                'fecha_ingreso_planilla' => $request->fecha_ingreso_planilla,
                'estado_registro' => 'A',
            ]);
            DB::commit();
            return response()->json(['resp' => 'Personal Creado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $personalID)
    {
        try {
            DB::beginTransaction();
            $personal = Personal::where('estado_registro', 'A')->find($personalID);
            if (!$personal) {
                return response()->json(['resp' => 'Personal no existe'], 200);
            }
            $persona = Persona::where('estado_registro', 'A')->find($personal->persona_id);
            $existePersona = Persona::where('estado_registro', 'A')->where('numero_documento', $request->numero_documento)->where('id', '!=', $persona->id)->first();
            if ($existePersona) {
                return response()->json(['resp' => 'El número de documento ya está en uso por otra persona'], 500);
            }
            $persona->update([
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'gmail' => $request->gmail,
                'tipo_documento_id' => $request->tipo_documento_id,
                'numero_documento' => $request->numero_documento
            ]);
            $personal->update([
                'personal_id' => $persona->id,
                'area_id' => $request->area_id,
                'habilidad' => $request->habilidad,
                'experiencia' => $request->experiencia
            ]);
            DB::commit();
            return response()->json(['resp' => 'Personal Actualizado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function delete($personalID)
    {
        try {
            DB::beginTransaction();
            $personal = Personal::where('estado_registro', 'A')->find($personalID);
            $persona = Persona::where('estado_registro', 'A')->where('id', $personal->persona_id)->first();
            $user=User::where('estado_registro','A')->where('personal_id',$personal->id)->first();
            $user_rol=UsuarioRol::where('estado_registro','A')->where('user_id',$user->id)->first();
            if (!$personal) {
                return response()->json(['resp' => 'Personal ya se encuentra Inhabilitado'], 200);
            }
            $persona->update([
                'estado_registro' => 'I'
            ]);
            $personal->update([
                'estado_registro' => 'I'
            ]);
            $user->update([
                'estado_registro'=>'I'
            ]);
            $user_rol->update([
                'estado_registro'=>'I'
            ]);
            DB::commit();
            return response()->json(['resp' => 'Personal Inhabilitado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function AsignarRol(Request $request, $personalID)
    {
        try {
            DB::beginTransaction();
            $personal = Personal::with('persona')->where('estado_registro', 'A')->find($personalID);
            if (!$personal) {
                return response()->json(['resp' => 'Personal No Disponible'], 200);
            }
            if (!$personal->persona->numero_documento) {
                return response()->json(["error" => "El número de documento está ausente o es nulo"], 400);
            }
            $roles = $request->rol;
            $user = User::updateOrCreate(
                [
                    'personal_id' => $personal->id
                ],
                [
                    'username' => $personal->persona->numero_documento,
                    'password' => $personal->persona->numero_documento,
                ]
            );
            //ELIMINAR LOS ROLES QUE NO SE ENVIAN POR REQUEST
            UsuarioRol::where('user_id', $user->id)->whereNotIn('rol_id', $roles)->delete();

            foreach ($roles as $rol) {
                UsuarioRol::updateOrCreate([
                    'rol_id' => $rol,
                    'user_id' => $user->id
                ]);
            }
            DB::commit();
            return response()->json(['resp' => 'Roles asignados Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function EnviarCredenciales($idPersonal)
    {
        try {
            DB::beginTransaction();

            $personal = Personal::where('estado_registro', 'A')->find($idPersonal);
            if (!$personal) {
                return response()->json(['resp' => 'No se encontró ningún Personal para enviar credenciales'], 404);
            }

            $persona = Persona::where('estado_registro', 'A')->where('id', $personal->persona_id)->first();
            if (!$persona) {
                return response()->json(['resp' => 'No se encontró ninguna Persona'], 404);
            }

            $user_personal = User::where('personal_id', $personal->id)->first();
            if (!$user_personal) {
                return response()->json(['resp' => 'No se encontró ningún usuario asociado al personal, por favor Asignar un Rol'], 404);
            }

            // Verificar si la contraseña coincide con el número de documento
            if (!Hash::check($persona->numero_documento, $user_personal->password)) {
                // Generar nueva contraseña temporal
                $nueva_contraseña = Str::random(8);
                $user_personal->update([
                    'password' => $nueva_contraseña
                ]);
                // Actualizar la contraseña en la base de datos
                $user_personal->password = Hash::make($nueva_contraseña);

                $credenciales = [
                    'username' => $persona->numero_documento,
                    'password' => $nueva_contraseña
                ];
            } else {
                $credenciales = [
                    'username' => $persona->numero_documento,
                    'password' => $persona->numero_documento
                ];
            }

            $correo = $persona->gmail;
            // Verificar si la dirección de correo es válida
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['resp' => 'Correo electrónico no válido o nulo'], 500);
            }
            $mail = new CredencialesMail($credenciales);
            Mail::to($correo)->send($mail);

            DB::commit();
            return response()->json(['resp' => 'Correo enviado'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function subirArchivo(Request $request)
    {
        try {
            // Validar que el archivo sea requerido, sea un archivo y tenga extensiones válidas
            $request->validate([
                'personal_excel' => 'required|file|mimes:xlsx,xls'
            ]);

            // Obtener el archivo de la solicitud
            $archivo = $request->file('personal_excel');
            $nombreArchivo = $archivo->getClientOriginalName();

            // Verificar si el archivo ya existe en el almacenamiento
            if (Storage::exists('public/personal/' . $nombreArchivo)) {
                return response()->json(['error' => 'Ya existe un archivo con este nombre. Por favor, renombre el archivo y vuelva a intentarlo.'], 500);
            }

            // Mover el archivo al almacenamiento en la carpeta adecuada dentro de `storage`
            $archivo->storeAs('public/personal', $nombreArchivo);

            // Importar el archivo usando la clase de importación
            Excel::import(new PersonalImport, storage_path('app/public/personal/' . $nombreArchivo));

            // Retornar una respuesta de éxito
            return response()->json(['resp' => 'Archivo subido y procesado correctamente'], 200);
        } catch (\Exception $e) {
            // Manejar errores
            return response()->json(['error' => 'Algo salió mal', 'message' => $e->getMessage()], 500);
        }
    }
    public function exportarPersonal()
    {
        return Excel::download(new PersonalExport, 'personal.xlsx');
    }
}
