<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
   use HasFactory, Notifiable, Auditable;

   protected $fillable = [
      'role',
      'description',
      'read',
      'create',
      'update',
      'delete',
      'more_permissions',
      'page_index',
      'active'
   ];

   protected $casts = [
      'active' => 'boolean',
   ];

   // Relación con usuarios
   public function users()
   {
      return $this->hasMany(User::class);
   }
}
