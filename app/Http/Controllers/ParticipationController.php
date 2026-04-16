<?php

namespace App\Http\Controllers;

use App\Models\ObjResponse;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParticipationController extends BaseCrudController
{
    protected $modelClass = Participation::class;
    // protected $resourceClass = ParticipationResource::class; // Opcional

    protected $validationRules = [
        'type'    => 'required|in:INE,Carta Identidad',
        'curp'    => 'required|string|size:18|regex:/^[A-Z]{4}\d{6}[HM][A-Z]{5}\d{2}$/|unique:participations,curp',
        'user_id' => 'required|exists:users,id',
        'active'  => 'boolean',
    ];

    protected $validationMessages = [
        'curp.unique' => 'Esta CURP ya ha sido registrada previamente.',
        'curp.regex'  => 'El formato de la CURP no es válido.',
    ];

    protected $selectLabel = 'curp';  // Para el selectIndex (si se necesita)

    protected $defaultOrderBy = ['created_at' => 'desc'];

    // Opcional: si quieres filtrar por usuario autenticado (por ejemplo, solo sus propias participaciones)
    // protected $useAuthFilter = true;
    // protected $indexQueryCallback = function($query, $request) {
    //     $query->where('user_id', auth()->id());
    // };

    /**
     * Opcional: personalizar la respuesta del método show para incluir relaciones.
     */
    public function show(Request $request, $id, $internal = false)
    {
        $participation = parent::show($request, $id, true);
        if (!$participation || $internal) return $participation;
        $participation->load('user');
        return ObjResponse::success($participation, 'Participación encontrada');
    }
}
