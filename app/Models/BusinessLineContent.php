<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class BusinessLineContent extends Model
{
    use Uploadable;
    public $timestamps = false;
    protected $guarded = [];

    protected $uploadableFiles = [
        'image_1' => [
            'folder' => 'businesslinecontents',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_2' => [
            'folder' => 'businesslinecontents',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_3' => [
            'folder' => 'businesslinecontents',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'pdf_1' => [
            'folder' => 'businesslinecontents',
            'rules' => 'mimes:pdf|max:20480',
        ],
        'pdf_2' => [
            'folder' => 'businesslinecontents',
            'rules' => 'mimes:pdf|max:20480',
        ]
    ];

    public function scopeOrder($query){
          return $query->orderBy('order');
    }

    public function scopePublished($query){
        return $query->where('published', 1)->order();
    }

    public function crud(){
        return $this->hasMany(BusinessContentCrud::class, 'content_id')->order();
    }
}
