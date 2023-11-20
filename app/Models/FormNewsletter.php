<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FormNewsletter extends Model {

    public $timestamps = false;
    protected $guarded = [];

    public function scopeOrder($query) {
        return $query->orderBy('id', 'DESC')->get();
    }



}