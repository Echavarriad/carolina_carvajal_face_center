<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
  
 	public $timestamps = false;
  protected $guarded = [];

  public function scopeOrder($query) {
    return $query->orderBy('name', 'ASC');
  }

  public function cities() {
      return $this->hasMany(City::class, 'state_id', 'id_state')->orderBy('name', 'ASC');
  }

  
}
