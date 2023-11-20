<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {

    public $timestamps = false;
    protected $guarded = [];

    public function contents() {
        return $this->morphMany(Content::class, 'section');
    }

    public function sliders(){
    	return $this->hasMany(Slider::class, 'section_id')->order()->where('model', 'App\Models\Section');
    }

}