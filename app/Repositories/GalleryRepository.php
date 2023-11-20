<?php

namespace App\Repositories;

use App\Models\Gallery;
/**
 * 
 */
class GalleryRepository extends BaseRepository
{
	CONST foreign_key =  'section_id';
	protected $section;
	
	public function __construct(Gallery $model)
	{

    $this->section = request()->get('foreign');
		parent::__construct($model, [], self::foreign_key, $this->section);
	}

	public function allGallery($model){
		$query = $this->model->order()
				->where('section_id', $this->section)
				->where('model', $model);    
		return $query;
	}

	public function getSection(){
		return $this->section;
	}

	public function orderGallery($value, $model){
    $order = $this->model::where(self::foreign_key, $value)->where('model', $model)->max('order'); 
    $order = empty($order) ? 1 :  ($order+=1);           
    return $order;
	}

}