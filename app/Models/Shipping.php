<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function scopeOrder($query){
          return $query->orderBy('name_city');
    }

    public function scopePublished($query){
        return $query->where('published', 1)->order();
    }
}
