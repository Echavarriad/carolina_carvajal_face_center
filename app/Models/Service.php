<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class Service extends Model
{
    use Uploadable;
    public $timestamps = false;
    protected $guarded = [];

    protected $uploadableFiles = [
        'banner' => [
            'folder' => 'service',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'banner_mobile' => [
            'folder' => 'service',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_1' => [
            'folder' => 'service',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_2' => [
            'folder' => 'service',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_3' => [
            'folder' => 'service',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'preview_video' => [
            'folder' => 'service',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'pdf_1' => [
            'folder' => 'service',
            'rules' => 'mimes:pdf|max:10240',
        ],
        'pdf_2' => [
            'folder' => 'service',
            'rules' => 'mimes:pdf|max:10240',
        ],
        'pdf_3' => [
            'folder' => 'service',
            'rules' => 'mimes:pdf|max:10240',
        ],
        'pdf_4' => [
            'folder' => 'service',
            'rules' => 'mimes:pdf|max:10240',
        ]
    ];

    public function scopeOrder($query){
        return $query->orderBy('id');
    }

    public function scopePublished($query){
        return $query->where('published', 1)->order();
    }

    public function gallery(){
        return $this->hasMany(ServiceGallery::class, 'service_id')->order();
    }

    public function scopeNext($query, $id){
        return $query->select(['title','slug'])->find($id);    
    }    
    
    public  function scopePrev($query, $id){
        return $query->select(['title','slug'])->find($id);    
    }
}
