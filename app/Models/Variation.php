<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model {
	
    public $timestamps = false;
    protected $guarded = [];
    
    public function haveSufficientStock($quantity){
     	if ($quantity > ($this->stock - $this->reserved_stock)) {
           return false;
      }
    	return true; 
    } 
  }