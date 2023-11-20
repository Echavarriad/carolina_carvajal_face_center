<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model{

    public $timestamps = false;
    protected $guarded = [];

    public function scopeOrder($query){
          return $query->orderBy('date', 'DESC');
    }

    public function scopePublished($query){
        return $query->where('published', true)->order();
    }

    public function scopeFeatured($query){
        return $query->where('featured', true)->published();
    }

    public function gallery(){
        return $this->hasMany(ArticleGallery::class, 'article_id');
    }

    public function formatDate(){
        /*$months = core()->getMonthsLarge();
        if(empty($this->date)){
            return '';
        }

        return  date('d', strtotime($this->date)) . ' de ' . $months[date('n', strtotime($this->date)) - 1] . ' de '  .  date('Y', strtotime($this->date));*/
    }
}
