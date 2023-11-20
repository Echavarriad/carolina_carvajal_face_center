<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class LandingProduct extends Model
{
    use Uploadable;
    public $timestamps = false;
    protected $guarded = [];

    protected $uploadableFiles = [
        'image' => [
            'folder' => 'landingproduct',
            'rules' => 'required|image|mimes:jpeg,bmp,png,svg|max:4096',
        ]
    ];

    public function scopeOrder($query){
          return $query->orderBy('order');
    }

    public function scopePublished($query){
        return $query->where('published', 1)->order();
    }

    public function scopeProducts($query, $landing, $show_in = null){
        if($show_in){
            return $query->published()->where('landing', $landing)->where('show_in', $show_in);
        }else{
            return $query->where('landing', $landing);
        }
        
    }
}
