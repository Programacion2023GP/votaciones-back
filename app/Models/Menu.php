<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Menu extends Model
{
   use HasFactory, Notifiable, Auditable;

   protected $fillable = [
      'menu',
      'caption',
      'type',
      'belongs_to',
      'url',
      'icon',
      'order',
      'show_counter',
      'counter_name',
      'others_permissions',
      'read_only',
      'active'
   ];

   protected $casts = [
      'show_counter' => 'boolean',
      'read_only' => 'boolean',
      'active' => 'boolean',
      'order' => 'integer',
   ];
}