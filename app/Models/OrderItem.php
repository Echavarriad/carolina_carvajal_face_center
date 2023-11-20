<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
	
    public $timestamps = true;
    protected $guarded = [];
    
    public function product(){
    	return $this->belongsTo(Product::class);
    }

    public function item_devolution(){
    		return $this->hasOne(ProductDevolution::class, 'order_item_id')->with('devolution', 'status');
    }

    public function order(){
    	return $this->belongsTo(Order::class)->with('user');
    }

    public function getPriceFormatAttribute($query){
        return core()->currency($this->attributes['price']);
    }

    public function getPriceTotalFormatAttribute($query){
        return core()->currency($this->attributes['price'] * $this->attributes['quantity']);
    }

    public function getTaxPercentFormatAttribute($query){
        return intval($this->attributes['tax_percent']);
    }

    public function getTaxAmountFormatAttribute($query){
        return core()->currency($this->attributes['tax_amount']);
    }

    public function getTotalFormatAttribute($query){
        return core()->currency($this->attributes['total']);
    }

}