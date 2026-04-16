<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\ObjResponse;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BallotController extends BaseCrudController
{
    protected $modelClass = Ballot::class;
    protected $validationRules = [
        'user_id' => 'required|exists:users,id|unique:ballots,user_id',
        'vote_1'  => 'required|exists:projects,id',
        'vote_2'  => 'required|exists:projects,id',
        'vote_3'  => 'required|exists:projects,id',
        'vote_4'  => 'required|exists:projects,id',
        'vote_5'  => 'required|exists:projects,id',
    ];
    protected $validationMessages = [
        'user_id.unique' => 'Esta casilla ya emitió su voto',
        'vote_1.exists'  => 'El proyecto seleccionado no existe',
        'vote_2.exists'  => 'El proyecto seleccionado no existe',
        'vote_3.exists'  => 'El proyecto seleccionado no existe',
        'vote_4.exists'  => 'El proyecto seleccionado no existe',
        'vote_5.exists'  => 'El proyecto seleccionado no existe',
    ];
    protected $defaultOrderBy = ['created_at' => 'desc'];

    // Sobrescribimos validateRequest para añadir la validación de que los proyectos no se repitan
    protected function validateRequest(Request $request, $id = null)
    {
        $rules = $this->validationRules;

        // Añadir reglas 'different' entre todos los campos de voto
        $voteFields = ['vote_1', 'vote_2', 'vote_3', 'vote_4', 'vote_5'];
        foreach ($voteFields as $i => $field) {
            $otherFields = array_diff($voteFields, [$field]);
            foreach ($otherFields as $other) {
                $rules[$field][] = "different:$other";
            }
        }

        // También podemos añadir una regla personalizada para mayor seguridad
        $validator = Validator::make($request->all(), $rules, $this->validationMessages);

        // Validación adicional: que no haya valores repetidos (redundante con 'different', pero seguro)
        $validator->after(function ($validator) use ($request) {
            $votes = [
                $request->vote_1,
                $request->vote_2,
                $request->vote_3,
                $request->vote_4,
                $request->vote_5,
            ];
            if (count(array_unique($votes)) !== count($votes)) {
                $validator->errors()->add('votes', 'No se puede seleccionar el mismo proyecto más de una vez.');
            }
        });

        return $validator;
    }

    // Opcional: mostrar la boleta con los proyectos cargados
    public function show(Request $request, $id, $internal = false)
    {
        $ballot = parent::show($request, $id, true);
        if (!$ballot || $internal) return $ballot;

        // Cargar relaciones para la respuesta
        $ballot->load(['vote1Project', 'vote2Project', 'vote3Project', 'vote4Project', 'vote5Project', 'user']);
        return ObjResponse::success($ballot, 'Boleta encontrada');
    }
}