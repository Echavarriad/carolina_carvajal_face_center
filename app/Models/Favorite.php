<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function scopeOrder($query){
        return $query->orderBy('created_at', 'DESC');
    }

    public function product(){
        return $this->hasOne(Product::class);
    }
}
