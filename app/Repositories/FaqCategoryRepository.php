<?php

namespace App\Repositories;

use App\Models\FaqCategory;


class FaqCategoryRepository extends BaseRepository
{
	
	public function __construct(FaqCategory $model)
	{
		parent::__construct($model);
	}

}