<?php

namespace App\Http\Controllers;

use App\Models\Casilla;
use App\Models\ObjResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CasillaController extends Controller
{
    /**
     * Mostrar lista de casillas.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::default()->getData(true); // convertir a array
        try {
            $auth = Auth::user();
            // $list = Casilla::orderBy('id', 'desc');
            $list = Casilla::orderBy('id', 'desc');
            if ($auth->role_id > 2) $list = $list->where("active", true);
            $list = $list->get();

            $response->data = ObjResponse::success()->getData(true); // convertir a array;
            $response->data["message"] = 'Peticion satisfactoria | Lista de casillas.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "CasillaController ~ index ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::error($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar listado para un selector.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function selectIndex(Response $response)
    {
        $response->data = ObjResponse::default()->getData(true); // convertir a array
        $auth = Auth::user();

        try {
            $list = Casilla::where('active', true)
                ->select('id as id', DB::raw("CONCAT(id, ' - ', TRIM(CONCAT(place, ' - ', COALESCE(location, '')))) as label"))
                ->orderBy(DB::raw("CONCAT(id, ' - ', TRIM(CONCAT(place, ' - ', COALESCE(location, ''))))"), 'asc');


            if ($auth->role_id == 3) {
                $list = $list->where('id', $auth->id);
            }

            $list = $list->get();

            $response->data = ObjResponse::success()->getData(true); // convertir a array;
            $response->data["message"] = 'peticion satisfactoria | lista de casillas.';
            $response->data["alert_text"] = "Casillas encontrados";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $msg = "CasillaController ~ selectIndex ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::error($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar casilla.
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $id
     * 
     * @return \Illuminate\Http\Response $response
     */
    public function createOrUpdate(Request $request, Response $response, Int $id = null)
    {
        $response->data = ObjResponse::default()->getData(true); // convertir a array
        try {
            $validator = $this->validateAvailableData($request, 'casillas', [
                [
                    'field' => 'type',
                    'label' => 'Tipo de área',
                    'rules' => ['string'],
                    'messages' => [
                        'string' => 'El tipo debe de ser Rural, Urbana o Especial.',
                    ]
                ],
                [
                    'field' => 'district',
                    'label' => 'Distrito',
                    'rules' => ['number'],
                    'messages' => [
                        'string' => 'El distrito debe ser numero.',
                    ]
                ],
                [
                    'field' => 'perimeter',
                    'label' => 'Perímetro',
                    'rules' => ['string'],
                    'messages' => [
                        'string' => 'El perímetro debe de ser un texto.',
                    ],
                    'validateRequired' => 0,
                ]
            ], $id);
            if ($validator->fails()) {
                $response->data = ObjResponse::error($validator->errors());
                $response->data["message"] = "Error de validación";
                $response->data["errors"] = $validator->errors();
                return response()->json($response);
            }

            $casilla = Casilla::find($id);
            if (!$casilla) $casilla = new Casilla();

            $casilla->fill($request->all());
            $casilla->save();


            $response->data = ObjResponse::success()->getData(true); // convertir a array;
            $response->data["message"] = $id > 0 ? 'peticion satisfactoria | casilla editado.' : 'peticion satisfactoria | casilla registrado.';
            $response->data["alert_text"] = $id > 0 ? "Casilla editado" : "Casilla registrado";
        } catch (\Exception $ex) {
            $msg = "CasillaController ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::error($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar casilla.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, Int $id, bool $internal = false)
    {
        $response->data = ObjResponse::default()->getData(true); // convertir a array
        try {
            $casilla = Casilla::find($id);
            if ($internal) return $casilla;

            $response->data = ObjResponse::success()->getData(true); // convertir a array;
            $response->data["message"] = 'peticion satisfactoria | casilla encontrado.';
            $response->data["result"] = $casilla;
        } catch (\Exception $ex) {
            $msg = "CasillaController ~ show ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::error($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Eliminar" (cambiar estado activo=0) casilla.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response, Int $id)
    {
        $response->data = ObjResponse::default()->getData(true); // convertir a array
        try {
            Casilla::where('id', $id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $response->data = ObjResponse::success()->getData(true); // convertir a array;
            $response->data["message"] = "peticion satisfactoria | casilla eliminado.";
            $response->data["alert_text"] = "Casilla eliminado";
        } catch (\Exception $ex) {
            $msg = "CasillaController ~ delete ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::error($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Activar o Desactivar" (cambiar estado activo=1/0).
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function disEnable(Response $response, Int $id, string $active)
    {
        $response->data = ObjResponse::default()->getData(true); // convertir a array
        try {
            Casilla::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "reactivar" ? 'reactivado' : 'desactivado';
            $response->data = ObjResponse::success()->getData(true); // convertir a array;
            $response->data["message"] = "peticion satisfactoria | casilla $description.";
            $response->data["alert_text"] = "Casilla $description";
        } catch (\Exception $ex) {
            $msg = "CasillaController ~ disEnable ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::error($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Eliminar uno o varios registros.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function deleteMultiple(Request $request, Response $response)
    {
        $response->data = ObjResponse::default()->getData(true); // convertir a array
        try {
            // echo "$request->ids";
            // $deleteIds = explode(',', $ids);
            $countDeleted = sizeof($request->ids);
            Casilla::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::success()->getData(true); // convertir a array;
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | registro eliminado.' : "peticion satisfactoria | registros eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Registro eliminado' : "Registros eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $msg = "CasillaController ~ deleteMultiple ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::error($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }
}
