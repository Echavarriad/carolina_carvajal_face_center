<?php

namespace App\Repositories;

use App\Models\Faq;

class FaqRepository extends BaseRepository
{
	CONST foreign_key =  'faq_category';
	protected $section;
	
	public function __construct(Faq $model)
	{
		$this->section = request()->get('foreign');
		parent::__construct($model, [], self::foreign_key, $this->section);
	}

	public function getSection(){
		return $this->section;
	}

}