<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Cart extends Model {

    protected $guarded = ['id'];

    public function items() {
        return $this->hasMany(CartItem::class);
    }

    public function address() {
        return $this->hasOne(CartAddress::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'customer_id');
    } 
    
    public function getCreatedAtAttribute(){
          return new Date($this->attributes['created_at']);
    }

    public function formatted($protected = false) {
        foreach ($this->items as $item) {
            $item->price = core()->currency($item->price);
            $item->has_discount = $item->discount_amount != 0 ? true:false;
            $item->discount_amount = core()->currency($item->discount_amount);
            $item->discount_value = (int) $item->discount_value;
            $item->old_price = core()->currency($item->old_price);
            $item->total = core()->currency($item->total);
            // $item->total_with_tax = core()->currency($item->total + $item->tax_amount);
        }
        $this->shipping_status = '';
        if($this->shipping_rate > 0){
            $this->shipping_status = 'ok';
        }
        $discount = $this->has_coupon ? $this->discount_value : 0;
        $total= $this->grand_total - $discount;
        // dd($total);
        $limit_shipping_free = config('settings.limit_shipping_free');
        if($this->shipping_rate){
            if($this->sub_total >= $limit_shipping_free){
                $this->percent_shipping_free= 100;
            }else{
                $this->percent_shipping_free = $this->sub_total * 100 / $limit_shipping_free;
            }
        }
        $this->shipping = core()->currency($this->shipping_rate);
        $this->discount_text = core()->currency($this->discount);
        $this->tax = core()->currency($this->tax_total);
        $this->sub_total = core()->currency($this->sub_total);
        $this->total = core()->currency($this->grand_total);
        
        return $this;
    }

    public function getFormatDateAttribute($query){
      return $this->created_at->format('d') . '/' . $this->created_at->format('m') . '/' .  $this->created_at->format('Y') . ' ' . $this->created_at->format('h') . ':' .  $this->created_at->format('i') . ' ' .  $this->created_at->format('A');
    }

}