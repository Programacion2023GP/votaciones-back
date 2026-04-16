<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends BaseCrudController
{
    protected $modelClass = Project::class;
    // protected $resourceClass = ProjectResource::class; // opcional
    protected $validationRules = [
        'folio'            => 'required|integer|unique:projects,folio',
        'assigned_district' => 'required|integer',
        'project_name'     => 'required|string|max:255',
        'project_place'    => 'required|string|max:255',
        'viability'        => 'boolean',
        'active'           => 'boolean',
    ];
    protected $validationMessages = [
        'folio.unique' => 'El folio ya está registrado',
    ];
    protected $selectLabel = 'project_name'; // para el selectIndex
    protected $defaultOrderBy = ['folio' => 'asc'];
    protected $useAuthFilter = true; // los usuarios normales solo ven activos
}
