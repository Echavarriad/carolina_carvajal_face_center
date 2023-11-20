<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductGallery extends Model {
  
    public $timestamps = false;
    protected $guarded = [];

    public function scopeOrder($query){
        return $query->orderBy('order');
    }

}