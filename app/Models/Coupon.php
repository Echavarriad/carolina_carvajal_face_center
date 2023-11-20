<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function scopeOrder($query){
        return $query->orderBy('id', 'DESC');
    }

    public function dateStart(){
        return date('j', strtotime($this->start)) . '/' . date('m', strtotime($this->start)) . '/' . date('Y', strtotime($this->start));
    }

    public function dateEnd(){
        return date('j', strtotime($this->end)) . '/' . date('m', strtotime($this->end)) . '/' . date('Y', strtotime($this->end));
    }

    public function isActive() {
        if (Carbon::now()->between(Carbon::parse($this->start), Carbon::parse($this->end)) && $this->status == 1) {
            return true;
        }
        return false;
    }

    public function user(){
        return $this->belongsTo(User::class, 'customer_id');
    }
}
