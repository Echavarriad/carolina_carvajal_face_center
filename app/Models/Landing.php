<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class Landing extends Model
{
    use Uploadable;
    public $timestamps = false;
    protected $guarded = [];

    protected $uploadableFiles = [
        'image_1' => [
            'folder' => 'landing',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_2' => [
            'folder' => 'landing',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_3' => [
            'folder' => 'landing',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ],
        'image_4' => [
            'folder' => 'landing',
            'rules' => 'image|mimes:jpeg,bmp,png,svg|max:4096',
        ]
    ];

    public function scopeOrder($query){
        return $query->orderBy('id');
    }

    public function scopeContent($query, $landing){
        return $query->where('landing', $landing);
    }

    public function fields() {
        return $this->hasOne(LandingContentField::class, 'content_id');
    }
}
