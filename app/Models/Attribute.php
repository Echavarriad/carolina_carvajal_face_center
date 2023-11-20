<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function scopeOrder($query){
        return $query->orderBy('order');
    }

    public function scopePublished($query){
        return $query->where('published', 1)->order();
    }

    public function options(){
        return $this->hasMany(AttributeOption::class, 'attribute_id')->orderBy('order');
    }
}
