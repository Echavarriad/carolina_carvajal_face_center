<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductAttribute extends Model {
  
    public $timestamps = false;
    protected $guarded = [];

    public function attribute(){
    	return $this->belongsTo(Attribute::class);
    }

    public function haveSufficientQuantity($quantity){
       return $this->quantity >= $quantity;
    }

}