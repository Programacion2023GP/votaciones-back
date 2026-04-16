<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ballot extends Model
{
    use HasFactory, Notifiable, Auditable;

    protected $table = 'ballots';

    protected $fillable = [
        'user_id',
        'vote_1',
        'vote_2',
        'vote_3',
        'vote_4',
        'vote_5',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vote1Project()
    {
        return $this->belongsTo(Project::class, 'vote_1');
    }

    public function vote2Project()
    {
        return $this->belongsTo(Project::class, 'vote_2');
    }

    public function vote3Project()
    {
        return $this->belongsTo(Project::class, 'vote_3');
    }

    public function vote4Project()
    {
        return $this->belongsTo(Project::class, 'vote_4');
    }

    public function vote5Project()
    {
        return $this->belongsTo(Project::class, 'vote_5');
    }

    // Método para obtener todos los proyectos votados (como colección)
    public function getVotedProjectsAttribute()
    {
        return Project::whereIn('id', [
            $this->vote_1,
            $this->vote_2,
            $this->vote_3,
            $this->vote_4,
            $this->vote_5,
        ])->get();
    }

    // Boot del modelo para validar que no se repitan proyectos
    protected static function booted()
    {
        static::saving(function ($ballot) {
            $votes = [
                $ballot->vote_1,
                $ballot->vote_2,
                $ballot->vote_3,
                $ballot->vote_4,
                $ballot->vote_5,
            ];
            // Eliminar nulos (si permitieras votos nulos, ajustar)
            $votes = array_filter($votes);
            if (count($votes) !== count(array_unique($votes))) {
                throw new \Exception('No se puede seleccionar el mismo proyecto más de una vez en la misma boleta.');
            }
        });
    }
}