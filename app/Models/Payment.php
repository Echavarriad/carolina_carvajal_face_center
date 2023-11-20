<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
	
    public $timestamps = false;
    protected $fillable = ['params' , 'order_id'];

    protected $casts = [
        'params' => 'array',
    ];
    
    public function getParamsAttribute($value){
    	return json_decode($value);
    }
}