<?php

namespace App\Http\Controllers;

use App\Models\Ballot;
use App\Models\ObjResponse;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BallotController extends BaseCrudController
{
    protected $modelClass = Ballot::class;
    protected $validationRules = [
        'user_id' => 'required|exists:users,id',
        'vote_1'  => 'nullable|exists:projects,id',
        'vote_2'  => 'nullable|exists:projects,id',
        'vote_3'  => 'nullable|exists:projects,id',
        'vote_4'  => 'nullable|exists:projects,id',
        'vote_5'  => 'nullable|exists:projects,id',
        'active'  => 'boolean',
    ];
    protected $validationMessages = [
        'user_id.exists' => 'Este usuario no existe',
        'vote_1.exists'  => 'El proyecto seleccionado no existe',
        'vote_2.exists'  => 'El proyecto seleccionado no existe',
        'vote_3.exists'  => 'El proyecto seleccionado no existe',
        'vote_4.exists'  => 'El proyecto seleccionado no existe',
        'vote_5.exists'  => 'El proyecto seleccionado no existe',
    ];
    protected $defaultOrderBy = ['created_at' => 'desc'];

    /**
     * Prepara los datos para validación y guardado.
     */
    protected function prepareForValidation(Request $request, $id = null)
    {
        $data = $request->all();
        foreach (['vote_1', 'vote_2', 'vote_3', 'vote_4', 'vote_5'] as $field) {
            if (isset($data[$field]) && ($data[$field] == 0 || $data[$field] === '0')) {
                $data[$field] = null;
            }
        }
        $request->replace($data);
    }

    /**
     * Sobrescribimos validateRequest para añadir:
     * 1. Regla 'different' solo entre valores no nulos y no cero.
     * 2. Validación de que no se repitan proyectos (valores > 0).
     */
    protected function validateRequest(Request $request, $id = null)
    {
        $rules = $this->validationRules;

        // // Asegurar que cada regla sea un array (para poder añadir más reglas)
        // foreach ($rules as $field => &$rule) {
        //     if (is_string($rule)) {
        //         $rule = explode('|', $rule);
        //     }
        // }


        // // Reglas base: user_id único, y vote_* nullable (acepta 0, null, etc.)
        // $rules = [
        //     'user_id' => 'required|exists:users,id|unique:ballots,user_id',
        //     'vote_1'  => 'nullable',
        //     'vote_2'  => 'nullable',
        //     'vote_3'  => 'nullable',
        //     'vote_4'  => 'nullable',
        //     'vote_5'  => 'nullable',
        //     'active'  => 'boolean',
        // ];

        $this->prepareForValidation($request, $id);

        $validator = Validator::make($request->all(), $rules, $this->validationMessages);

        // Validación adicional: proyectos distintos entre sí (solo valores >0)
        $validator->after(function ($validator) use ($request) {
            $votes = [];
            foreach (['vote_1', 'vote_2', 'vote_3', 'vote_4', 'vote_5'] as $field) {
                $value = $request->input($field);
                if (is_numeric($value) && $value > 0) {
                    $votes[] = $value;
                }
            }
            if (count($votes) !== count(array_unique($votes))) {
                $validator->errors()->add('votes', 'No se puede seleccionar el mismo proyecto más de una vez.');
            }
        });

        return $validator;

        // Validación adicional personalizada
        $validator->after(function ($validator) use ($request) {
            // Obtener solo los valores de voto que son > 0
            $votes = array_filter([
                $request->vote_1,
                $request->vote_2,
                $request->vote_3,
                $request->vote_4,
                $request->vote_5,
            ], function ($val) {
                return !is_null($val) && $val > 0;
            });

            // 1. No deben haber duplicados entre los valores > 0
            if (count($votes) !== count(array_unique($votes))) {
                $validator->errors()->add('votes', 'No se puede seleccionar el mismo proyecto más de una vez.');
            }

            // 2. (Opcional) Validar que al menos se haya seleccionado un proyecto
            // if (empty($votes)) {
            //     $validator->errors()->add('votes', 'Debe seleccionar al menos un proyecto.');
            // }

            // 3. (Opcional) Validar que el número de votos no supere 5 (ya implícito)
        });

        return $validator;
    }

    // Opcional: Si quieres personalizar la respuesta del show para incluir relaciones
    // public function show(Request $request, $id, $internal = false)
    // {
    //     $ballot = parent::show($request, $id, true);
    //     if (!$ballot || $internal) return $ballot;
    //     $ballot->load('user', 'vote1Project', 'vote2Project', 'vote3Project', 'vote4Project', 'vote5Project');
    //     return ObjResponse::success($ballot, 'Boleta encontrada');
    // }

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
