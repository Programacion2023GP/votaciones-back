<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Participation extends Model
{
    use HasFactory, Notifiable, Auditable;

    protected $fillable = [
        'type',
        'curp',
        'user_id',
        'active'
    ];

    protected $casts = [
        'type' => 'string',
        'active' => 'boolean',
    ];

    // Relación con el usuario (casilla)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para activos
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
