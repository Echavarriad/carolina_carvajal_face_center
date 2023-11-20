<?php



namespace App\Models;

use App\Traits\Uploadable;

use Illuminate\Database\Eloquent\Model;



class Content extends Model {

    use Uploadable;

    public $timestamps = false;
    protected $guarded = [];
    protected $uploadableFiles = [
        'file_1' => [
            'folder' => 'content',
            'rules' => 'mimes:pdf|max:10000',
        ],
        'file_2' => [
            'folder' => 'content',
            'rules' => 'mimes:pdf|max:10000',
        ]
    ];

    public function fields() {
        return $this->hasOne(ContentField::class);
    }

    public function section(){
     return $this->belongsTo(Section::class, 'section_id');
}



}