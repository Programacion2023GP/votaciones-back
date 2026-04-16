<?php

namespace App\Http\Controllers;

use App\Models\Casilla;
use App\Models\ObjResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasillaController extends BaseCrudController
{
    protected $modelClass = Casilla::class;
    // protected $resourceClass = CasillaResource::class; // Opcional

    protected $validationRules = [
        'type'      => 'nullable|string|in:Rural,Urbana,Especial',
        'district'  => 'nullable|integer',
        'perimeter' => 'nullable|string|max:255',
        'place'     => 'nullable|string|max:255',
        'location'  => 'nullable|string|max:255',
        'active'    => 'boolean',
    ];

    protected $validationMessages = [
        'type.in' => 'El tipo debe ser Rural, Urbana o Especial.',
    ];

    // Para el método selectIndex: personalizamos la consulta porque queremos un label compuesto
    protected $selectLabel = 'id'; // Valor por defecto, pero lo sobrescribiremos en el método

    // Orden por defecto (opcional)
    protected $defaultOrderBy = ['id' => 'desc'];

    // Filtro automático por active según rol (role_id > 2 solo ven activos)
    protected $useAuthFilter = true;

    /**
     * Sobrescribimos selectIndex para mostrar un label personalizado:
     * "id - place - location"
     */
    public function selectIndex(Request $request)
    {
        try {
            $auth = auth()->user();
            $query = $this->modelClass::where('active', true)
                ->select('id as id', DB::raw("CONCAT(id, ' - ', TRIM(CONCAT(place, ' - ', COALESCE(location, '')))) as label"))
                ->orderBy(DB::raw("CONCAT(id, ' - ', TRIM(CONCAT(place, ' - ', COALESCE(location, ''))))"), 'asc');

            if ($auth && $auth->role_id == 3) {
                $query->where('id', $auth->id);
            }

            $list = $query->get();

            return ObjResponse::success($list, 'Lista select obtenida');
        } catch (\Exception $ex) {
            $msg = get_class($this) . " ~ selectIndex: " . $ex->getMessage();
            \Log::error($msg);
            return ObjResponse::serverError('Error al obtener lista select', $ex);
        }
    }

    // Opcional: si necesitas personalizar la respuesta del método show para incluir relaciones
    // public function show(Request $request, $id, $internal = false)
    // {
    //     $casilla = parent::show($request, $id, true);
    //     if (!$casilla || $internal) return $casilla;
    //     return ObjResponse::success($casilla, 'Casilla encontrada');
    // }
}
