<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model {  

	protected $table = 'customer_address';
  protected $guarded = [];  

  public function state() {
          return $this->belongsTo(State::class, 'state_id', 'id_state');
 	}

 	public function city() {
      return $this->belongsTo(City::class);
 	}

 	public function scopePrincipal($query){
      return $query->where('principal', 1)->first();
  }
}