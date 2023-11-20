<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class ManagementTeam extends Model
{
    use Uploadable;
    public $timestamps = false;
    protected $guarded = [];

    protected $uploadableFiles = [
        'image' => [
            'folder' => 'managementteam',
            'rules' => 'required|image|mimes:jpeg,bmp,png,svg|max:4096',
        ]
    ];

    public function scopeOrder($query){
          return $query->orderBy('order');
    }
}
