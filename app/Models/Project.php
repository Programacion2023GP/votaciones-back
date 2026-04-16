<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{
    use HasFactory, Notifiable, Auditable;

    protected $fillable = [
        'folio',
        'assigned_district',
        'project_name',
        'project_place',
        'viability',
        'active'
    ];

    protected $casts = [
        'folio' => 'integer',
        'assigned_district' => 'integer',
        'viability' => 'boolean',
        'active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // Relación con Ballot (un proyecto puede ser votado muchas veces como vote_1, vote_2, etc.)
    // Como son 5 relaciones separadas, definimos cada una (opcional)
    public function ballotsAsVote1()
    {
        return $this->hasMany(Ballot::class, 'vote_1');
    }

    public function ballotsAsVote2()
    {
        return $this->hasMany(Ballot::class, 'vote_2');
    }

     public function ballotsAsVote3()
    {
        return $this->hasMany(Ballot::class, 'vote_3');
    }

     public function ballotsAsVote4()
    {
        return $this->hasMany(Ballot::class, 'vote_4');
    }

     public function ballotsAsVote5()
    {
        return $this->hasMany(Ballot::class, 'vote_5');
    }
}