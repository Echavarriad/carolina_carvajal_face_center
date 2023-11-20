<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {

    protected $guarded = [];

    protected $casts = [
        'additional' => 'array',
    ];

    public function product(){
    	return $this->hasOne(Product::class , 'id'  , 'product_id');
    }
   
}