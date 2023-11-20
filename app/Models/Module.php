<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {

    public $timestamps = false;
    protected $fillable = ['logo', 'type' , 'name' , 'description' , 'order','settings' , 'url'];

    protected $casts = [
      'settings' => 'array'
    ];

    public function scopeActive($query){
      return $query->where('active' , 1)->orderBy('order');
    }

    public function scopeCarriers($query){
      return $query->where('type' , 'carrier')->where('active' , 1)->orderBy('order');
    }

    public function scopeGateways($query){
      return $query->where('type' , 'payment')->where('active' , 1)->orderBy('order');
    }

    public function getSettings(){
    	return (object) $this->settings;
    }
   

}