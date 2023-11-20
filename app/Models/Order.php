<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Order extends Model {

    public $timestamps = true;
    protected $guarded = ['id'];

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'customer_id');
    }

   	public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function address(){
      return $this->hasOne(OrderAddress::class);
    }

    public function payment(){
      return $this->hasOne(Payment::class);
    }

    public function getCreatedAtAttribute(){
        return new Date($this->attributes['created_at']);
     }

    public function getOrderDateAttribute(){
        return new Date($this->attributes['order_date']);
    }

    /* public function getSubtotalCustomerFormatAttribute($query){
      return core()->currency($this->attributes['sub_total'] + $this->attributes['tax_amount']);
    }

    public function getSubtotalFormatAttribute($query){
  		return core()->currency($this->attributes['sub_total']);
    }

    public function getTaxFormatAttribute($query){
      return core()->currency($this->attributes['tax_amount']);
    }

    public function getTotalFormatAttribute($query){
      if ($this->attributes['apply_tax']) {
          $total = $this->attributes['grand_total'] + $this->attributes['tax_amount'];
      }else{
          $total = $this->attributes['grand_total'];
      }
  		return core()->currency($total);
    }

    public function getDiscountFormatAttribute($query){
      return core()->currency($this->attributes['discount_amount']);
    }

    public function getShippingFormatAttribute($query){
      return core()->currency($this->attributes['shipping_rate']);
    } */

    public function getStatusBadgeAttribute($query) {
      	return '<span class="status badge badge-secondary" style="background: ' . $this->status->color . ';color:#fff;">' . $this->status->name . '</span>';
    }

    public function getDateFormatAttribute($query){
    	return $this->created_at->format('d') . '-' . $this->created_at->format('m') . '-' .  $this->created_at->format('Y');
    }

    public function getFormatOrderDateAttribute($query){
      return $this->order_date->format('d') . '-' . $this->order_date->format('m') . '-' .  $this->order_date->format('Y') . ' ' . $this->order_date->format('h') . ':' .  $this->order_date->format('i') . ' ' .  $this->order_date->format('A');
    }

    public function formatted($protected = false) {
        foreach ($this->items as $item) {
            $item->total = core()->currency($item->total);
            $item->price = core()->currency($item->price);
            $item->image = asset('uploads/' . $item->image);
            $item->date = $this->created_at->format('d') . '/' . $this->created_at->format('m') . '/' .  $this->created_at->format('Y');
        }
        return $this;
    }

    public function scopeForUpdateInSiigo($query){
      return $query->where('status_id', 2)->where('update_stock_in_siigo', false)->with('items')->get();
    }

}
