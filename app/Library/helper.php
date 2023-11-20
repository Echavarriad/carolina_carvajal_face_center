<?php

use App\Models\{Section, Content};
use Illuminate\Support\Facades\View;


function set_content($id){
  	$section = Section::with('contents')->find($id);
  	View::share('section', $section);
  	set_seo($section);
}

function set_seo($value){
    $title= !empty($value->meta_title) ? $value->meta_title . ' | ' : '';
    View::share('meta_title', $title . config('settings.shop_name'));
    View::share('meta_description', $value->meta_description);
    View::share('meta_keywords', $value->meta_keywords);
}

function set_banner($id){    
    $section = Section::find(10);
    $content = Content::find($id);
    $section->contents[0]->text_1  = $content->text_1;
    $section->contents[0]->image_1 = $content->image_1;
    $section->contents[0]->alt_1   = $content->alt_1;
    $section->contents[0]->tit_1   = $content->tit_1;
    $section->contents[0]->image_2 = $content->image_2;
    $section->contents[0]->alt_2   = $content->alt_2;
    $section->contents[0]->tit_2   = $content->tit_2;

    View::share('section', $section);
}



if (!function_exists('core')) {
    function core() {
        return app()->make(\App\Shop\Core::class);
    }
}





