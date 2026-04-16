<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Casilla extends Model
{
   use HasFactory, Notifiable, Auditable;

   protected $fillable = [
      'type',
      'district',
      'perimeter',
      'place',
      'location',
      'active'
   ];

   protected $casts = [
      'active' => 'boolean',
      'district' => 'integer',
   ];

   // Relación con participaciones
   public function participations()
   {
      return $this->hasMany(Participation::class);
   }

   // Relación con usuarios (opcional, si se asigna un responsable)
   public function users()
   {
      return $this->hasMany(User::class);
   }
}