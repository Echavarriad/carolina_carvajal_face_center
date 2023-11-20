<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model {

    use NodeTrait;
    public $timestamps = false;
    protected $guarded = [];

    private static $tree = [];

    public static function tree() {
        $data = self::orderBy('lft')->get()->toArray();
        foreach ($data as $category) {
            if ($category['parent_id'] == 0) {
                $category['children'] = self::childs($data, $category['id']);
                self::$tree[] = $category;
            }
        }
        return self::$tree;
    }

    public function children() { 
        return $this->hasMany(Category::class, 'parent_id')->orderBy('_lft'); 
    }

    public static function childs($data, $parentId) {
        $childs = [];
        foreach ($data as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $item['children'] = self::childs($data, $item['id']);
                $childs[] = $item;
            }
        }
        return $childs;
    }

}