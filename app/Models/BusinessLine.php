<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class BusinessLine extends Model
{
    use Uploadable;
    public $timestamps = false;
    protected $guarded = [];

    protected $uploadableFiles = [
        'banner' => [
            'folder' => 'businessline',
            'rules' => 'required|image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'banner_mobile' => [
            'folder' => 'businessline',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'icon' => [
            'folder' => 'businessline',
            'rules' => 'required|image|mimes:jpeg,bmp,png,svg|max:4096',
        ]
    ];

    public function content(){
        return $this->hasMany(BusinessLineContent::class, 'business_line_id')->published()->with('crud');
    }

    public function scopeOrder($query){
          return $query->orderBy('order');
    }

    public function scopePublished($query){
        return $query->where('published', 1)->order();
    }
}
